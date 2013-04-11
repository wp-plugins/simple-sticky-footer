<?php
/*
  Plugin Name: Simple Sticky Footer
  Plugin URI: http://www.sandorkovacs.ro/simple-sticky-footer-wordpress-plugin/
  Description: Lightweight Sticky Footer plugin
  Author: Sandor Kovacs
  Version: 1.0
  Author URI: http://sandorkovacs.ro/
 */

// Do the magic stuff
add_action('wp_footer', 'simple_sf');

add_action('wp_head', 'simple_sf_ban_init');
add_action('admin_init', 'simple_sf_ban_init');
add_action('admin_menu', 'register_simple_sf_ban_submenu_page');

function simple_sf_ban_init() {
    /* Register our stylesheet. */
    wp_register_style('simple-sticky-footer', plugins_url('simple-sticky-footer.css', __FILE__));
    wp_enqueue_style('simple-sticky-footer');
}

function register_simple_sf_ban_submenu_page() {
    add_submenu_page(
            'themes.php', __('Sticky Footer'), __('Sticky Footer'), 'edit_posts', 'simple-simple-sticky-footer', 'simple_sf_ban_callback');
}

function simple_sf_ban_callback() {

    // By Default activate do not redirect for logged in users
    if (!get_option('simple_sf_width'))
        update_option('simple_sf_width', '960px');

    // form submit  and save values
    if (isset($_POST['submit'])) {
        update_option('simple_sf_pid', $_POST['page_id']);
        update_option('simple_sf_width', $_POST['simple_sf_width']);
        update_option('simple_sf_style', $_POST['simple_sf_style']);
        update_option('simple_sf_hide', isset($_POST['simple_sf_hide']) ? 1 : 0 );
    }

    // read values from option table

    $width = get_option('simple_sf_width');
    $pid = get_option('simple_sf_pid');
    $style = get_option('simple_sf_style');
    $simple_sf_hide = (intval(get_option('simple_sf_hide')) == 1 ) ? 1 : 0;
    ?>

    <div class="wrap" id='simple-sf'>
        <div class="icon32" id="icon-options-general"><br></div><h2><?php _e('Sticky Footer Configuration'); ?></h2>

        <p>
            <?php _e('Configure sticky footer by completing the following form.') ?>
        </p>    

        <form action="" method="post">
  
            <h3>Required</h3>
            
            <p>
            <label for='simple_sf_page'><strong><?php _e('Select page'); ?></strong></label> <br/>
            <?php wp_dropdown_pages(array('selected' => $pid)); ?>
            </p>
            

            <h3>Optional</h3>
            <p>
                <label for='simple_sf_width'><strong><?php _e('Width'); ?></strong></label> <br/>
                <input  type='text' name='simple_sf_width' id='simple-sf-width' 
                        value='<?php echo $width; ?>' 
                        placeholder='<?php _e('960px') ?>' />
            </p>


            <p>
                <label for='simple_sf_style'><strong><?php _e('Addition CSS Rules'); ?></strong></label> <br/>
                <textarea name='simple_sf_style' id='simple-sf-style' cols="50" rows="20"><?php echo $style; ?></textarea>
                <br/>
                <small><?php _e('Here you can define addition CSS rules like: rounded borders, gradient background, shadows, etc ...  ') ?></small>
                <br/>
                <small><?php _e('Do not use { }, just enter the css properties ex: background:gray;border-top:1px;') ?></small>

            </p>



            <p>
                <label for='simple_sf_hide'><strong><?php _e('Hide'); ?></strong></label> <br/>
                <input  type='checkbox' name='simple_sf_hide' id='simple-sf-hide'  value='1' 
                        <?php echo ($simple_sf_hide == 1 ) ? " checked='checked'" : "" ?>         />
                <br/>
                <small><?php _e('If this box is checked the Sticky Footer will be a hidden div. It is usefull when you want to put some tracking scripts or other hidden HTML elements ') ?></small>
            <p>

            <p>
                <input type='submit' name='submit' value='<?php _e('Save') ?>' />
            </p>


        </form>

    </div>

    <?php
}

// Do the magic stuff
function simple_sf() {
    $pid = intval(get_option('simple_sf_pid'));
    if ($pid < 1)
        return '';

    $pid = get_option('simple_sf_pid');
    $width =  get_option('simple_sf_width');
    $hide =  get_option('simple_sf_hide');
    $style =  get_option('simple_sf_style');
    $query = new WP_Query(array('page_id'=>$pid));
    // The Loop
    while ($query->have_posts()) :$query->the_post();
        ?>

        <div id="simple-sticky-footer-container">
            <div id="simple-sticky-footer" 
                 style="width: <?php echo $width; ?>; <?php echo ($hide == 1) ? 'display:none': '' ?>; <?php echo (!empty($style)) ? $style: ''?>;">
                <?php the_content(); ?>
            </div>
        </div>


        <?php
    endwhile;
    wp_reset_postdata();
}