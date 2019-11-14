<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="text" class="form-control" name="s" id="s" placeholder="<?php esc_html_e( 'Type Keyword', 'wpestate' ); ?>" />
    <button class="wpresidence_button"  id="submit-form"><?php esc_html_e('Search','wpestate');?></button>
    <?php wp_nonce_field( 'wpestate_search_nonce', 'wpestate_search_nonce_field' ) ?>
</form>
