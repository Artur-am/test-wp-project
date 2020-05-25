<?php

require_once('class/getPost.php');

const LANG_DOMAIN = '';

add_action( 'wp_enqueue_scripts', 'realEstate_scripts' );
function realEstate_scripts()
{
    wp_enqueue_style('realEstate-style', get_stylesheet_uri());

    wp_enqueue_script( 'main--js', get_stylesheet_directory_uri() . '/main.js', array( ), false, true);

    wp_localize_script(
        'main--js',
        'realEstate_main',
        array(
            'ajax_url'    => admin_url( 'admin-ajax.php' ),
            'ajax_nonce'  => wp_create_nonce('dfg-fdg-string-nonce')
        )
    );
}


add_action( 'after_setup_theme', 'realEstate_setup' );
function realEstate_setup()
{
// Langs
    if( function_exists('pll_register_string') )
    {
        pll_register_string('real_estate', 'Нерухомість', 'real_estate');
        pll_register_string('more', 'Еще', 'real_estate');
        PLL_Admin_Strings::register_string('area', 'Площа', 'real_estate-card');
        PLL_Admin_Strings::register_string('Price', 'Вартість', 'real_estate-card');
        PLL_Admin_Strings::register_string('address', 'Адреса', 'real_estate-card');
        PLL_Admin_Strings::register_string('dwelling_place', 'Житлова площа', 'real_estate-card');
        PLL_Admin_Strings::register_string('floor', 'Поверх', 'real_estate-card');
    }
}

function realEstate_lang($string = '')
{
    if(function_exists('pll__'))
    {
       return pll__( $string );
    }

    return $string;
}

function realEstate_lang_group($group)
{
    if(
        empty($group) ||
        ( false === class_exists( 'PLL_Admin_Strings') )
    ){
        return '';
    }

    return array_values( wp_list_filter( ( PLL_Admin_Strings::get_strings() ), array('context'=> $group ) ) );
}

function realEstate_get_terms($tax)
{
    $terms = get_terms($tax);
    if ( !empty( $terms ) && !is_wp_error( $terms ) )
    {
        return $terms;
    }
    return array();
}

function HTML_post_card()
{
    $categories = '';
    $items = '';

    foreach( get_the_terms( get_the_ID(), 'type_of_non_bridge') as $cat )
    {
        $categories .= '<a class="pl-2 pr-2 badge badge-primary" href="'. esc_url( get_category_link( $cat ) ) .'">'.
            $cat->name
        .'</a>';
    }

    foreach( realEstate_lang_group('real_estate-card') as $list )
    {
        $items .= '<li class="row p-2 ml-0 mr-0 justify-content-between">
            <div class="font-weight-bold">'. $list['string'] .'</div>
            <div>'.
                ( ($list['name'] == 'address') ? '<a href="'. get_term_link( get_field($list['name']), 'test_tags' ) .'">'. get_field($list['name']) .'</a>'
                    : get_field($list['name']) ) .'
            </div>
        </li>';
    }

    return '
    <div class="col-sm-6 mb-5">
        <div class="card">
            <a href="'.get_permalink().'">'.get_the_post_thumbnail() .'</a>
            <div class="card-body">
                <h5 class="mb-1 card-title">'. get_the_title() .'</h5>
                <div class="mb-2 card__categories">'. $categories .'</div>
                <div class="card-text" data-textlength="116">'. get_the_excerpt() .'</div>
                <ul class="pl-0 mt-3 card__group list-group-flush">'.
                    $items
                .'</ul>
            </div>
        </div>
    </div>';
}

add_action( 'wp_ajax_loadmore', 'realEstate_loadmore' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_loadmore', 'realEstate_loadmore' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}
function realEstate_loadmore()
{
    if( !wp_verify_nonce( $_POST['nonce_code'], 'dfg-fdg-string-nonce' ) )
    {
       die();
    }
    $post_types = array(
        'real_estate' => true
    );

    if(
        (false === array_key_exists($_POST['post_type'], $post_types)) ||
        !( (int) $_POST['page'] )
    ){
        wp_send_json_error( [] );
    }

    $res = '';
    $args = array(
        'post_type' => 'real_estate',
        'paged' => $_POST['page'],
        'posts_per_page' => 4
    );

    if( getPost::set_posts( $args ) )
    {
        while( getPost::get_post() )
        {
            $res .= HTML_post_card();
        }
    }

    wp_send_json_success( array(
        'posts' => $res)
    );
}