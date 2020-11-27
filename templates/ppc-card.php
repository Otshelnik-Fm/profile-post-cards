<?php
/*  Шаблон дополнения Profile Post Cards https://codeseller.ru/products/profile-post-cards/
  Он заменяет шаблон вывода дополнения PublicPost (Публикация)
  Версия шаблона: v1.1
  Шаблон вывода списка публикаций в вкладке личного кабинета
  Этот шаблон можно скопировать в папку WP-Recall шаблонов по пути: ваш-сайт/wp-content/wp-recall/templates/
  - сделать нужные вам правки и изменения и он будет подключаться оттуда
  Работа с шаблонами описана тут: https://codeseller.ru/?p=11632
 */
?>
<?php
global $post, $posts, $ratings;

$target = ppc_is_target_link();
?>

<div id="ppc_wrapper" class="ppc_box">
    <?php
    foreach ( $posts as $postdata ) {
        foreach ( $postdata as $post ) {
            ?>
            <article class="ppc_item">
                <a title="Перейти" <?php echo $target; ?> class="ppc_img" href="/?p=<?php echo $post->ID; ?>">
                    <?php echo ppc_get_image( $post->ID, $post->post_title ); ?>
                    <?php echo ppc_status( $post->post_status ); ?>
                </a>

                <div class="ppc_flight">
                    <?php do_action( 'ppc_flight_icons', $post->ID, $post->post_status, $post ); ?>
                </div>

                <?php echo ppc_get_post_title_link( $post->ID, $post->post_title, $post->post_status ); ?>

                <div class="ppc_footer">
                    <span class="ppc_date"><?php echo mysql2date( 'd.m.Y', $post->post_date ); ?></span>

                    <span class="ppc_icons">
                        <?php echo ppc_get_comment( $post->comment_status, $post->comment_count ); ?>
                        <?php echo ppc_get_rating( $post->ID, $ratings ); ?>
                    </span>
                </div>
            </article>
        <?php } ?>
    <?php } ?>
</div>