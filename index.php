<?php

/*

  ╔═╗╔╦╗╔═╗╔╦╗
  ║ ║ ║ ╠╣ ║║║ https://otshelnik-fm.ru
  ╚═╝ ╩ ╚  ╩ ╩

 */

/* настройки */
require_once 'inc/settings.php';

// стили
add_action( 'rcl_enqueue_scripts', 'ppc_styles', 10 );
function ppc_styles() {
    rcl_enqueue_style( 'ppc_style', rcl_addon_url( 'assets/css/ppc-style.css', __FILE__ ), true );
}

add_filter( 'rcl_template_path', 'ppc_replace_template_postlist', 10, 2 );
function ppc_replace_template_postlist( $path, $templateName ) {
    if ( $templateName != 'posts-list.php' )
        return $path;

    if ( file_exists( RCL_TAKEPATH . 'templates/ppc-card.php' ) )
        return RCL_TAKEPATH . 'templates/ppc-card.php';

    return rcl_addon_path( __FILE__ ) . 'templates/ppc-card.php';
}

function ppc_get_image( $post_id, $title ) {
    $img_id   = get_post_meta( $post_id, '_thumbnail_id', true );
    $img_data = image_downsize( $img_id, 'medium' );
    $img      = ( ! empty( $img_data ) ) ? $img_data[0] : rcl_get_option( 'ppc_img' );

    return '<img loading="lazy" alt="' . $title . '" src="' . $img . '">';
}

function ppc_get_post_title_link( $post_id, $title, $post_status ) {
    if ( empty( $title ) )
        $title = "<i class='rcli fa-ellipsis-h' aria-hidden='true'></i>";

    global $user_ID;

    if ( ! rcl_is_office( $user_ID ) ) {
        if ( $post_status == 'pending' || $post_status == 'draft' || $post_status == 'private' ) {
            return $title;
        }
    }

    $target = ppc_is_target_link();

    return '<a ' . $target . ' title="Перейти" class="ppc_link" href="/?p=' . $post_id . '">' . $title . '</a>';
}

function ppc_is_target_link() {
    $target = '';
    if ( rcl_get_option( 'ppc_target', 'target' ) === 'target' ) {
        $target = 'target="_blank"';
    }

    return $target;
}

function ppc_get_comment( $comment_status, $comment_count ) {
    if ( $comment_status != 'open' )
        return;

    $out = '<span class="ppc_comment" title="Комментариев">';
    $out .= '<i class="rcli fa-comments-o" aria-hidden="true"></i>';
    $out .= '<span class="comment-value">' . $comment_count . '</span>';
    $out .= '</span>';

    return $out;
}

function ppc_get_rating( $post_id, $ratings ) {
    if ( ! function_exists( 'rcl_format_rating' ) )
        return;

    $rtng = (isset( $ratings[$post_id] )) ? $ratings[$post_id] : 0;

    $out = '<span class="ppc_rating" title="Рейтинг">';
    $out .= '<i class="rcli fa-heartbeat" aria-hidden="true"></i>';
    $out .= '<span class="rating-value">' . rcl_format_rating( $rtng ) . '</span>';
    $out .= '</span>';

    return $out;
}

function ppc_status( $post_status ) {
    $status = '';
    if ( $post_status == 'pending' )
        $status = '<div class="ppc_status ppc_pending">На утверждении</div>';
    elseif ( $post_status == 'draft' )
        $status = '<div class="ppc_status ppc_draft">Черновик</div>';
    elseif ( $post_status == 'private' )
        $status = '<div class="ppc_status ppc_private">Личное</div>';

    if ( empty( $status ) )
        return;

    return $status;
}

add_action( 'ppc_flight_icons', 'ppc_edit_author_link', 10, 3 );
function ppc_edit_author_link( $post_id, $post_status, $post ) {
    if ( ! is_user_logged_in() )
        return;

    global $user_ID;

    if ( rcl_is_office( $user_ID ) || current_user_can( 'edit_post', $post_id ) ) {
        if ( rcl_get_option( 'ppc_edit', 'no' ) === 'no' )
            return;

        global $current_user;
        $ppc_user_info = get_userdata( $current_user->ID );

        add_filter( 'get_edit_post_link', 'rcl_edit_post_link', 100, 2 );

        if ( $post->post_author != $user_ID ) {
            $author_info = get_userdata( $post->post_author );
            if ( $ppc_user_info->user_level < $author_info->user_level )
                return;
        }

        $frontEdit = rcl_get_option( 'front_editing', array( 0 ) );
        if ( false !== array_search( $ppc_user_info->user_level, $frontEdit ) || $ppc_user_info->user_level >= rcl_get_option( 'consol_access_rcl', 7 ) ) {

            if ( $ppc_user_info->user_level < 10 && rcl_is_limit_editing( $post->post_date ) )
                return;

            $content = '<a href="' . get_edit_post_link( $post_id ) . '" class="ppc_edit" title="Редактировать">';
            $content .= '<i class="rcli fa-pencil" aria-hidden="true"></i>';
            $content .= '</a>';

            echo $content;
        }
    }
}

// поддержка universe activity modal
add_action( 'ppc_flight_icons', 'ppc_get_post_modal_una', 10, 2 );
function ppc_get_post_modal_una( $post_id, $post_status ) {
    if ( ! function_exists( 'unam_shortcode' ) )
        return;

    if ( $post_status != 'publish' )
        return;

    $atts = [
        'id'    => $post_id,
        'text'  => '',
        'class' => 'unam_simple ppc_edit',
        'icon'  => 'fa-clone',
    ];
    echo unam_shortcode( $atts );
}
