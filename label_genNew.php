<?php

require './functions/functions.php';

$examTypeYear = 'GATCE 2016';
$link = mysqli_connect('localhost', 'root', '', 'formtwoextras') or die(mysqli_error($link));
$newQuery = "SELECT * FROM plist";
$result = mysqli_query($link, $newQuery) or die(mysqli_error($link));
unlink('andikafile.txt');
while ($data = mysqli_fetch_array($result)) {
    $paperPerEnvolope = $data['copies'];
    $totalPaperNumber = $data['papersEnclosed'];
    $paperName = $data['subjectName'];
    $centreNumber = $data['schoolNumber'];
    $centreName = $data['schoolName'];
    $region = $data['region'];
    $subjectCode = $data['subjectCode'];
    $numberOfenvolope = ceil(($totalPaperNumber / $paperPerEnvolope));
    $twoStikersRow = ceil($numberOfenvolope / 2); //Number of two stickers row
    $oddStickerNumbers = 1; //Setting initial  odd stiker
    $evenStickerNumbers = 2; // Setting initial even stiker 
    $openFile = fopen('andikafile.txt', 'a');

    for ($value = 1; $value <= $twoStikersRow; $value++) {

        //Writting Examination type and year line
        $leftStiker = margin(2) . str_repeat(' ', 20) . fix_width($examTypeYear, 40);
        $rightStiker = ($evenStickerNumbers <= $numberOfenvolope) ? margin(2) . str_repeat(' ', 20) . fix_width($examTypeYear, 40) : '';
        $stikerRow = $leftStiker . $rightStiker . str_repeat(PHP_EOL, 4);
        fwrite($openFile, $stikerRow);

        //Writting paper numbers, center number, Papername and copies enclosed line
        $remainder = $totalPaperNumber % $paperPerEnvolope;
        $paperPerEnvolope = ($oddStickerNumbers == $numberOfenvolope) ? ($remainder == 0 ? $paperPerEnvolope : $remainder) : $paperPerEnvolope;
        $leftStiker = margin(2) . fix_width($subjectCode, 10) . fix_width($centreNumber, 10) . fix_width($paperName, 23) . str_repeat(' ', 3) . fix_width($paperPerEnvolope, 14);
        $paperPerEnvolope = ($evenStickerNumbers == $numberOfenvolope) ? ($remainder == 0 ? $paperPerEnvolope : $remainder) : $paperPerEnvolope;
        $rightStiker = ($evenStickerNumbers <= $numberOfenvolope) ? margin(2) . fix_width($subjectCode, 10) . fix_width($centreNumber, 10) . fix_width($paperName, 23) . str_repeat(' ', 3) . fix_width($paperPerEnvolope, 14) : '';
        $stikerRow = $leftStiker . $rightStiker . str_repeat(PHP_EOL, 3);
        fwrite($openFile, $stikerRow);

        //Writting center name and region name line
        $leftStiker = margin(2) . fix_width($centreName, 32) . str_repeat(' ', 3) . fix_width($region, 25);
        $rightStiker = ($evenStickerNumbers <= $numberOfenvolope) ? margin(2) . fix_width($centreName, 32) . str_repeat(' ', 3) . fix_width($region, 25) : '';
        $stikerRow = $leftStiker . $rightStiker . str_repeat(PHP_EOL, 3);
        fwrite($openFile, $stikerRow);

        //Writting number of bages out of total bages line
        $leftStiker = margin(2) . str_repeat(' ', 8) . fix_width($oddStickerNumbers . str_repeat(' ', 7) . $numberOfenvolope, 52);
        $rightStiker = ($evenStickerNumbers <= $numberOfenvolope) ? margin(2) . str_repeat(' ', 8) . fix_width($evenStickerNumbers . str_repeat(' ', 7) . $numberOfenvolope, 52) : ''; //Creating right stiker only if number of stiker is not greater than envelope number
        $stikerRow = margin(2) . $leftStiker . $rightStiker . str_repeat(PHP_EOL, 26); //Combining left and write stiker to form two stiker row
        fwrite($openFile, $stikerRow);

        $oddStickerNumbers = $evenStickerNumbers + 1; // Next odd sticker
        $evenStickerNumbers = $oddStickerNumbers + 1; // Next even sticker
    }
    fclose($openFile);
}