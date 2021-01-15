<?php
// phpcs:disable Generic.Files.LineLength.MaxExceeded                              
// phpcs:disable Generic.Files.LineLength.TooLong                                  

/**
 * @var mixed[] $view
 */
?>
<h2>GCS Deployment Options</h2>

<form
    name="wp2static-gcs-save-options"
    method="POST"
    action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

    <?php wp_nonce_field( $view['nonce_action'] ); ?>
    <input name="action" type="hidden" value="wp2static_gcs_save_options" />

<table class="widefat striped">
    <tbody>

        <tr>
            <td style="width:50%;">
                <label
                    for="<?php echo $view['options']['bucket']->name; ?>"
                ><?php echo $view['options']['bucket']->label; ?></label>
            </td>
            <td>
                <input
                    id="<?php echo $view['options']['bucket']->name; ?>"
                    name="<?php echo $view['options']['bucket']->name; ?>"
                    type="text"
                    value="<?php echo $view['options']['bucket']->value !== '' ? $view['options']['bucket']->value : ''; ?>"
                />
            </td>
        </tr>

        <tr>
            <td style="width:50%;">
                <label
                    for="<?php echo $view['options']['keyFilePath']->name; ?>"
                ><?php echo $view['options']['keyFilePath']->label; ?></label>
            </td>
            <td>
                <input
                    id="<?php echo $view['options']['keyFilePath']->name; ?>"
                    name="<?php echo $view['options']['keyFilePath']->name; ?>"
                    type="text"
                    value="<?php echo $view['options']['keyFilePath']->value !== '' ? $view['options']['keyFilePath']->value : ''; ?>"
                />
            </td>
        </tr>

        <tr>
            <td style="width:50%;">
                <label
                    for="<?php echo $view['options']['remotePath']->name; ?>"
                ><?php echo $view['options']['remotePath']->label; ?></label>
            </td>
            <td>
                <input
                    id="<?php echo $view['options']['remotePath']->name; ?>"
                    name="<?php echo $view['options']['remotePath']->name; ?>"
                    type="text"
                    value="<?php echo $view['options']['remotePath']->value !== '' ? $view['options']['remotePath']->value : ''; ?>"
                />
            </td>
        </tr>

        <tr>
            <td style="width:50%;">
                <label
                    for="<?php echo $view['options']['cacheControl']->name; ?>"
                ><?php echo $view['options']['cacheControl']->label; ?></label>
            </td>
            <td>
                <input
                    id="<?php echo $view['options']['cacheControl']->name; ?>"
                    name="<?php echo $view['options']['cacheControl']->name; ?>"
                    type="text"
                    value="<?php echo $view['options']['cacheControl']->value !== '' ? $view['options']['cacheControl']->value : ''; ?>"
                />
            </td>
        </tr>

        <tr>
            <td style="width:50%;">
                <label
                    for="<?php echo $view['options']['objectACL']->name; ?>"
                ><?php echo $view['options']['objectACL']->label; ?></label>
            </td>
            <td>
                <select
                    id="<?php echo $view['options']['objectACL']->name; ?>"
                    name="<?php echo $view['options']['objectACL']->name; ?>"
                >
                    <option
                        <?php if ( $view['options']['objectACL']->value === 'publicRead' ) {
                            echo 'selected'; } ?>
                        value="publicRead">publicRead</option>
                    <option
                        <?php if ( $view['options']['objectACL']->value === 'private' ) {
                            echo 'selected'; } ?>
                        value="private">private</option>
                </select>
            </td>
        </tr>

    </tbody>
</table>

<br>

    <button class="button btn-primary">Save GCS Options</button>
</form>

