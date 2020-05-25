<?php

class RPT {

   public static function Register($args){

       $data = array();

       if(empty($args['args']))
       {
           $data = array(
               'public'          => true,
               'has_archive'     => true,
               'supports'        => array( 'title', 'excerpt', 'editor', 'thumbnail' ),
               'show_in_rest'    => true,
               'menu_icon'       => $args['icon']
           );
       }else
       {
           $data = $args['args'];
       }

       if(!empty($args['labels']))
       {
           $data['labels'] = self::Labels( $args['labels'] );
       }

       self::create_post( $args['type'], $data );

       if(!empty($args['taxonomy']))
       {
           $data = array(
               'hierarchical'               => true,
               'public'                     => true,
               'show_ui'                    => true,
               'show_admin_column'          => true,
               'show_in_rest'               => true,
               'show_tagcloud'              => false
           );

           self::create_taxonomy( $args['taxonomy'], $args['type'], $data );
       }

   }

   private static function Labels($labels){
       $name = mb_strtolower($labels['name']);
       $names = mb_strtolower($labels['names']);
       $add_title = ( empty($labels['add_title']) ) ? $name : $labels['add_title'];

       return array(
            'name'                       => $labels['names'],
            'singular_name'              => $names,
            'all_items'                  => $labels['names'],
            'edit_item'                  => 'Редактировать ' . $name,
            'add_new'                    => 'Добавить ' . $add_title,
            'view_item'                  => 'Посмотреть ' . $name,
            'update_item'                => 'Сохранить ' . $name,
            'add_new_item'               => 'Добавить новую ' . $name,
            'new_item_name'              => 'Новая ' . $labels['names'],
            'parent_item'                => 'Родительская ' . $labels['names'],
            'search_items'               => 'Поиск по ' . $name,
            'popular_items'              => 'Популярные метки',
            'separate_items_with_commas' => 'Список Меток (разделяются запятыми)',
            'add_or_remove_items'        => 'Добавить или удалить метку - 8',
            'choose_from_most_used'      => 'Выбрать метку',
            'not_found'                  => 'Метки не заданы',
            'back_to_items'              => 'Назад на страницу меток',
            'not_found'                  => 'Ничего не найдено',
            'not_found_in_trash'         => 'В корзине ничего не найдено'
       );
   }

   private static function create_post($type, $args){
       register_post_type( $type, $args);
       flush_rewrite_rules();
   }

   private static function create_taxonomy($taxonomy, $type, $args ){
       register_taxonomy( strtolower($taxonomy), $type, $args );
       flush_rewrite_rules();
   }

}