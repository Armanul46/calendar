<?php
/*
Plugin Name: Calendar
Plugin URI: http://example.com/calender
Description: A calendar plugin for WordPress.
Version: 1.0
Author: Your Name
Author URI: http://example.com
License: GPL2
*/

use Elementor\Core\Utils\Str;

function enqueue_bootstrap_admin() {
    wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' );
  }
  add_action( 'admin_enqueue_scripts', 'enqueue_bootstrap_admin' );
  

// Add a menu item for the plugin
function my_calendar_menu() {
    add_menu_page(
        'Calendar',
        'Calendar',
        'manage_options',
        'calendar',
        'my_calendar_page'
    );
}
add_action('admin_menu', 'my_calendar_menu');

// Create the file explorer page
function my_calendar_page() { 
    $settings_page_link = admin_url( 'admin.php?page=calendar' );
    if( isset( $_GET['ym'] ) ) {
        $ym = $_GET['ym'];
    } else {
        $ym = date('Y-m');
    }
    $timestamp = strtotime( $ym . '-01' );
   
    $today = date("Y-m-j");

    $title = date("F, Y", $timestamp );
    $prev  = date("Y-m", strtotime( '-1 month', $timestamp ) );
    $next  = date("Y-m", strtotime( '+1 month', $timestamp ) );
    
    $day_count = date( 't', $timestamp );
    $numeric_day = date( 'N', $timestamp ); 
    $weeks = [];
    $week = '';
    
    $week .= str_repeat( '<td></td>', $numeric_day - 1);
    
    for( $day = 1; $day <= $day_count; $day++, $numeric_day++ ) {
        
        $date = $ym . '-' . $day;
        $week .= '<td>' . $day . '</td>';

        if( $numeric_day % 7 == 0 || $day == $day_count ) {
            if( $day == $day_count && $numeric_day % 7 != 0 ) {
                $week .= str_repeat( '<td></td>', 7 - $numeric_day % 7 );
            }
            $weeks[] = '<tr>' . $week . '</tr>';
            $week = '';
        }
    }
    ?>
    <style>
        .list-inline {
            text-align: center;
        }
    </style>
    <div class="container">
        <ul class="list-inline">
            <li class="list-inline-item prev"><a href="<?php echo add_query_arg( 'ym', $prev, $settings_page_link ); ?>">Prev</a></li>
            <li class="list-inline-item"><?php echo esc_html( $title ); ?></li>
            <li class="list-inline-item next"><a href="<?php echo add_query_arg( 'ym', $next, $settings_page_link ); ?>">Next</a></li>
        </ul>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>M</th>
                <th>T</th>
                <th>W</th>
                <th>T</th>
                <th>F</th>
                <th>S</th>
                <th>S</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach( $weeks as $week ) {
                    echo $week;
                }
            ?>
        </tbody>
    </table>
    <?php
}
