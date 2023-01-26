<?php
    // if( !defined('DS') ) define( 'DS', DIRECTORY_SEPARATOR );


    recurseCopy('../data/timetables/2021-2022 Birinchi semestr/','../data/timetables/2021-2022 Ikkinchi semestr');

    // function recurse_copy( $src, $dst ) { 

    //     $dir = opendir( $src ); 
    //     @mkdir( dirname( $dst ) );

    //     while( false !== ( $file = readdir( $dir ) ) ) { 
    //         if( $file != '.' && $file != '..' ) { 
    //             if( is_dir( $src . DS . $file ) ) { 
    //                 recurse_copy( $src . DS . $file, $dst . DS . $file ); 
    //             } else { 
    //                 copy( $src . DS . $file, $dst . DS . $file ); 
    //             } 
    //         } 
    //     } 
    //     closedir( $dir ); 
    // }
    function recurseCopy(
        string $sourceDirectory,
        string $destinationDirectory,
        string $childFolder = ''
    ): void {
        $directory = opendir($sourceDirectory);
    
        if (is_dir($destinationDirectory) === false) {
            mkdir($destinationDirectory);
        }
    
        if ($childFolder !== '') {
            if (is_dir("$destinationDirectory/$childFolder") === false) {
                mkdir("$destinationDirectory/$childFolder");
            }
    
            while (($file = readdir($directory)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
    
                if (is_dir("$sourceDirectory/$file") === true) {
                    recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                } else {
                    copy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                }
            }
    
            closedir($directory);
    
            return;
        }
    
        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
    
            if (is_dir("$sourceDirectory/$file") === true) {
                recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
            else {
                copy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
        }
    
        closedir($directory);
    }
?>
