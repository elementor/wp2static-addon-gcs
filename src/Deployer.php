<?php

namespace WP2StaticGCS;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Google\Cloud\Storage\StorageClient;
use WP2Static\WsLog;

class Deployer {

    const DEFAULT_NAMESPACE = 'wp2static-addon-gcs/default';

    // prepare deploy, if modifies URL structure, should be an action
    // $this->prepareDeploy();

    // options - load from addon's static methods

    public function __construct() {}

    public function uploadFiles( string $processed_site_path ) : void {
        // check if dir exists
        if ( ! is_dir( $processed_site_path ) ) {
            return;
        }

        $namespace = self::DEFAULT_NAMESPACE;

        // instantiate GCS client
        $gcs = self::gcsClient();
        if ( ! $gcs ) {
            WsLog::l( 'Failed to create GCS client' );
            return;
        }

        $bucket = $gcs->bucket( Controller::getValue( 'bucket' ) );

        // iterate each file in ProcessedSite
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $processed_site_path,
                RecursiveDirectoryIterator::SKIP_DOTS
            )
        );

        $object_acl = Controller::getValue( 'objectACL' );
        $options = [
            'metadata' => [],
            'predefinedAcl' => $object_acl === '' ? 'publicRead' : $object_acl,
        ];

        $cache_control = Controller::getValue( 'cacheControl' );
        if ( $cache_control ) {
            $options['metadata']['cacheControl'] = $cache_control;
        }

        $base_options = $options;

        foreach ( $iterator as $filename => $file_object ) {
            $base_name = basename( $filename );
            if ( $base_name != '.' && $base_name != '..' ) {
                $real_filepath = realpath( $filename );

                // TODO: do filepaths differ when running from WP-CLI (non-chroot)?

                $cache_key = str_replace( $processed_site_path, '', $filename );

                if ( ! $real_filepath ) {
                    $err = 'Trying to deploy unknown file to GCS: ' . $filename;
                    \WP2Static\WsLog::l( $err );
                    continue;
                }

                // Standardise all paths to use / (Windows support)
                $filename = str_replace( '\\', '/', $filename );

                if ( ! is_string( $filename ) ) {
                    continue;
                }

                $gcs_key =
                    Controller::getValue( 'remotePath' ) ?
                    Controller::getValue( 'remotePath' ) . '/' .
                    ltrim( $cache_key, '/' ) :
                    ltrim( $cache_key, '/' );
                $options['name'] = $gcs_key;

                $mime_type = MimeTypes::guessMimeType( $filename );
                if ( 'text/' === substr( $mime_type, 0, 5 ) ) {
                    $mime_type = $mime_type . '; charset=UTF-8';
                }
                $options['metadata']['contentType'] = $mime_type;

                $options_hash = md5( (string) json_encode( $options ) );
                $body = file_get_contents( $filename );

                if ( ! $body ) {
                    WsLog::l( "file_get_contents failed for: $filename" );
                } else {
                    $body_hash = md5( (string) $body );
                    $hash = md5( $options_hash . $body_hash );

                    $is_cached = \WP2Static\DeployCache::fileisCached(
                        $cache_key,
                        $namespace,
                        $hash,
                    );

                    if ( $is_cached ) {
                        continue;
                    }

                    $object = $bucket->upload(
                        $body,
                        $options
                    );

                    \WP2Static\DeployCache::addFile( $cache_key, $namespace, $hash );
                }
            }
        }

    }

    public static function gcsClient() : ?StorageClient {
        $key_file = Controller::getValue( 'keyFilePath' );

        if (
            $key_file
        ) {
            try {
                return new StorageClient(
                    [
                        'keyFilePath' => $key_file,
                    ]
                );
            } catch ( \Google\Cloud\Core\Exception\GoogleException $e ) {
                WsLog::l( 'Error creating StorageClient: ' . $e->getMessage() );
                return null;
            }
        }

        return new StorageClient();
    }

}
