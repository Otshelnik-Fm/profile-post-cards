<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter( 'rcl_options', 'ppc_addon_options' );
function ppc_addon_options( $options ) {
    // создаем блок
    $options->add_box( 'ppc_box_id', array(
        'title' => 'Настройки Profile Post Cards',
        'icon'  => 'fa-picture-o'
    ) );


    // создаем группу 1
    $options->box( 'ppc_box_id' )->add_group( 'ppc_group_1', array(
        'title' => 'Настройки Profile Post Cards:'
    ) )->add_options( array(
        [
            'title'  => 'Путь до изображения, если нет',
            'type'   => 'text',
            'slug'   => 'ppc_img',
            'help'   => 'Если у публикации нет прикреплённого изображения - то возьмется дефолтное отсюда.<br>'
            . 'Обязательно укажите путь до изображения.<br>'
            . 'Загрузите её в вашу <a href="https://yadi.sk/i/_xUaW-njWzntrw" target="_blank">медиабиблиотеку</a> и скопируйте урл до неё в это поле.<br><br>'
            . 'Например:<br> http://ваш-сайт.ru/wp-content/uploads/2019/04/pict.jpg',
            'notice' => 'Обязательно!',
        ],
        [
            'title'  => 'Переход по ссылкам',
            'type'   => 'select',
            'slug'   => 'ppc_target',
            'values' => [ 'target' => 'В новой вкладке', 'self' => 'Открывать в текущей вкладке' ],
            'help'   => 'Выбирайте, как будут открываться ссылки по клику на запись. По умолчанию ссылка открывает в новой вкладке запись. Но есть и вариант перехода в этой же вкладке',
            'notice' => 'По умолчанию "В новой вкладке"',
        ],
        [
            'title'  => 'Выводить иконку редактирования записи?',
            'type'   => 'select',
            'slug'   => 'ppc_edit',
            'values' => [ 'no' => 'Нет', 'yes' => 'Да' ],
            'help'   => 'Выбрав "Да" в списке записей будет выводиться ссылка на редактирование записи. Но это большее кол-во запросов к базе. '
            . '<br> Ссылка на редактирование записи всегда доступна на странице этой записи. Но для удобства ее можно вывести и в списке публикаций автора.',
            'notice' => 'По умолчанию "Нет"',
        ]
    ) );

    $my_adv = '';
    if ( ! rcl_exist_addon( 'profile-post-cards-pro' ) ) {
        $text   = 'Я выпустил похожее, премиум дополнение, с большими возможностями:<br/>'
            . '<a href="https://codeseller.ru/products/profile-post-cards-pro/" title="Перейти к описанию" target="_blank">"Profile Post Cards PRO"</a>'
            . ' - Список публикаций в профиле пользователя карточками и выбор.<br/><hr>'
            . 'Предлагаю ознакомиться с его функционалом.';
        $text   .= '<style>#rcl-field-ppc_2 .rcl-notice__text{text-align:left;margin-left:18px;}</style>';
        $args   = [
            'type'  => 'success', // info,success,warning,error,simple
            'icon'  => 'fa-money',
            'title' => 'Хочешь профессиональную версию?',
            'text'  => $text,
        ];
        $my_adv = rcl_get_notice( $args );
    }

    if ( ! empty( $my_adv ) ) {
        $options->box( 'ppc_box_id' )->add_group( 'ppc_group_2', array(
            'title' => ''
        ) )->add_options( array(
            [
                'type'    => 'custom',
                'slug'    => 'ppc_2',
                'content' => $my_adv
            ]
        ) );
    }

    $my_una = '';
    if ( ! rcl_exist_addon( 'universe-activity-modal' ) ) {
        $text   = 'Премиум дополнение:<br/>'
            . '<a href="https://codeseller.ru/products/universe-activity-modal/" title="Перейти к описанию" target="_blank">"Universe Activity Modal"</a>'
            . ' - Просмотр публикаций в модальном (всплывающем) окне.<br/><hr>'
            . 'Пользователи не будут далеко уходить - открывай окна в списке публикаций пользователя как в instagram.<br/>'
            . 'Подробности и скриншоты <a href="https://codeseller.ru/products/profile-post-cards/" title="Перейти" target="_blank">тут</a>';
        $text   .= '<style>#rcl-field-ppc_3 .rcl-notice__text{text-align:left;margin-left:18px;}</style>';
        $args   = [
            'type'  => 'info', // info,success,warning,error,simple
            'icon'  => 'fa-money',
            'title' => 'Открывать записи в всплывающем окне надо?',
            'text'  => $text,
        ];
        $my_una = rcl_get_notice( $args );
    }

    if ( ! empty( $my_una ) ) {
        $options->box( 'ppc_box_id' )->add_group( 'ppc_group_3', array(
            'title' => ''
        ) )->add_options( array(
            [
                'type'    => 'custom',
                'slug'    => 'ppc_3',
                'content' => $my_una
            ]
        ) );
    }

    return $options;
}
