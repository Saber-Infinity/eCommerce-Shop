<?php


function lang( $phrase ) {
    
    static $lang = array(
        
        // Navbar Words (Links)
        
        'Homepage'   => 'Home',
        'CATEGORISE' => 'Categories',
        'ITEMS'      => 'Items',
        'MEMBERS'    => 'Members',
        'COMMENTS'   => 'Comments'        
    );
    
    return $lang[$phrase];
}

