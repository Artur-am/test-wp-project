<?php

    class getPost
    {
        private static $data;

        public static function set_posts( $args2 )
        {
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => get_option( 'posts_per_page' )
            );
            foreach($args2 as $key => $value)
            {
                $args[$key] = $value;
            }

            self::$data = new WP_Query( $args );
            return self::$data->have_posts();
        }

        public static function get_post()
        {
            if( self::$data->have_posts() )
            {
                self::$data->the_post();
                return true;
            }
            self::$data = null;
            return false;
        }
    }