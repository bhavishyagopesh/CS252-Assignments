
<h1> Mongo-php Assignment2 </h1>
<form method="post">
    <input type="submit" name="test1" id="test1" value="Most crime reported" /><br/>
</form>

<form method="post">
    <input type="submit" name="test2" id="test2" value="Most ineffecient police stations" /><br/>
</form>

<form method="post">
    <input type="submit" name="test3" id="test3" value="Most and Min crime laws" /><br/>
</form>

<?php


//Html 
function part1()
{
    $client = new MongoClient("mongodb://localhost:27017");
    $db = $client->db1;
    $collection = $db->col1;
    $temp = $collection->distinct("DISTRICT");
    $r1 = array();//stores the inefficiency of police stations
    $r2 = array();//name for the police stations
    $t = -1;
    foreach ($temp as $i => $doc) {

        $q = array('DISTRICT'=>$doc);
        $num = $collection->count($q);

        array_push($r1,$num);
        array_push($r2,$doc);
        if ($num > $t){
            $t = $num ;
        }
    }



    for($i=0; $i<count($r1)-1; $i++) {
        
        if ($r1[$i] == $t)
        {
            echo $r2[$i].' -> Frequency: '.$r1[$i];
            echo "<br>";
        }
    }

}

if(array_key_exists('test1',$_POST)){
   part1();
}



function part2()
{

    // Code part 2

    $client = new MongoClient("mongodb://localhost:27017");
    $db = $client->db1;
    $collection = $db->col1;
    $temp = $collection->distinct("PS");
    $r1 = array();//stores the inefficiency of police stations
    $r2 = array();//name for the police stations
    $t = -1;
    $result = "asds";
    foreach ($temp as $i => $doc) {
        
        //echo $doc;
        $q1 = array('PS' => $doc);
        $denom = $collection->count($q1);
        $q2 = array('PS' => $doc, 'Status' => "Pending");
        $num = $collection->count($q2);
        $ineff = $num/(float)$denom;
        array_push($r1,$ineff);
        array_push($r2,$doc);
            
        if($ineff > $t )
        {	
            $t = $ineff;
        }
    }


    for($i=0; $i<count($r1)-1; $i++) {
        
        if ($r1[$i] == $t)
        {
            echo $r2[$i].' -> Fractional Inefficiency: '.$r1[$i];
            echo "<br>";
        }
    }
}

if(array_key_exists('test2',$_POST)){
   part2();
}

// Code part 3

function part3()
{
    $client = new MongoClient("mongodb://localhost:27017");
    $db = $client->db1;
    $collection = $db->col1;
    $temp = $collection->find();
    $M = -1;

    $m = 150000;
    $arr = array();

    foreach ($temp as $i ) {

        for($j=0; $j<count($i['Act_Section']); $j++) {

            array_push($arr,$i['Act_Section'][$j]);
        }
        //echo '<br>';
    }

    $arr1  = array_count_values($arr);


    foreach ($arr1 as $j  => $k  ) {


        if ($k > $M ){
            $Mans = $j;
            $M = $k;
        }

        if ($k < $m ){
            $mans = $j;
            $m = $k;
        }
    }

    echo 'Max occuring Laws';
    echo '<br>';
    foreach ($arr1 as $j  => $k  ) {


        if ($k == $M ){
            echo 'Act_Section: ' . $j .  ' -> Frequency: ' .$M;
            echo '<br>';
        }

    }

    echo '<br>';
    echo '<br>';

    echo 'Min occuring Laws';
    echo '<br>';

    foreach ($arr1 as $j  => $k  ) {


        if ($k == $m ){
            echo 'Act_Section: ' . $j .  ' -> Frequency: ' .$m;
            echo '<br>';
        }

    }


}

if(array_key_exists('test3',$_POST)){
   part3();
}




?>

