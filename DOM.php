<html>
<body>
<head>
    <style>


        select{ 
            padding: 6px;
            border-radius: 6px;
            text-align: center;
            margin: 47px;
        }

       #PapName{
            border-right:none;
            border-right: none;
            font-size: 37px;
            text-align: center;
            padding: 20px;
        }

        button{
            border-radius: 6px;
            padding: 6px 12px;
        }

        #innerTable{
            border-left:none;
            border-right:none;
            padding-bottom: 31px;
            vertical-align:top;
            padding-top: 12px;
        }   

        .reviews{
            background-color:#d203fc;
        }

        .news{
            background-color:#03fcfc;
        }


       .news, .reviews{
            border:none;
            text-align:center;
        }

  

        #tableInside{
            margin-right: 6px;
            margin-left: 10px;
            border:none;
        }

        #PapType{
            border-left: none;
            font-size: 18px;
            writing-mode: tb-rl;
            transform: rotate(-180deg);
            position: relative;
            left: -25px;
            text-align: end;
            padding-bottom: 25px;
        }
        
        #PapSub{
            border: none;
            text-align: start;
            font-size: 18px;
            writing-mode: tb-rl;
            transform: rotate(-180deg);
            padding-top: 25px;
        }

    </style>
</head>
<form method='POST' action='DOM.php'> 
<?php
 
    //getting data for the form
    $xml = file_get_contents('https://wwwlab.webug.se/examples/XML/articleservice/papers/'); 
    $Dom = new DomDocument; 
    $Dom->preserveWhiteSpace = FALSE;
    $Dom->loadXML($xml); 
    
 
    echo "<select name='paper'>"; 
    $Newpep= $Dom->getElementsByTagName('NEWSPAPER');
    foreach ($Newpep as $newspaper){
        echo "<option value='".$newspaper->getAttribute("TYPE")."'>";
        echo $newspaper->getAttribute("NAME");
    }
    echo "</select>";
    echo"<button>Submit!</button>";
    echo"</form>";


    if(isset($_POST['paper'])){
        $tip=$_POST['paper'];
    }else{
        $tip="Morning_Edition";
    }

    $url="https://wwwlab.webug.se/examples/XML/articleservice/articles/?paper=".$tip;
    $data = file_get_contents($url);
    $Dom1 = new DomDocument; 
    $Dom1->preserveWhiteSpace = FALSE;
    $Dom1->loadXML($data); 

    echo "<table border='1'>";

    $artikles = $Dom1->getElementsByTagName('NEWSPAPER');
    foreach ($artikles as $Sidor){

        echo "<tr>";
        echo "<td id='PapName'>".$Sidor ->getAttribute("NAME")."</td>";
        echo "<td id='PapSub'>".$Sidor ->getAttribute("SUBSCRIBERS")."</td>";
        echo "<td id='PapType'>".$Sidor ->getAttribute("TYPE")."</td>";

        foreach ($Sidor ->childNodes as $child){

            echo "<td id='innerTable'><table id='tableInside' border='1'>";
            echo "<tr>";

            if($child ->getAttribute("DESCRIPTION")=='News'){

                echo "<td class='news'>".$child ->getAttribute("ID")."</td>";
                echo "<td class='news'>".$child ->getAttribute("TIME")."</td>";  
                echo "<td class='news'>".$child ->getAttribute("DESCRIPTION")."</td>";
                echo "</tr>";

                foreach ($child->childNodes as $secondChild){
                    $elements = $secondChild->nodeName;

                    if($secondChild->nodeName=="HEADING"){
                        echo "<tr><td class='news' colspan='3'><h3>".$secondChild->nodeValue."</h3>";

                        }else if($secondChild->nodeName=="STORY"){
                            echo "<div>";
                            
                            foreach($secondChild->childNodes as $thirdChild){
                                echo "<p>".$thirdChild->nodeValue."</p>";
                            }
                        echo "</div></td></td></tr>";
                        }
                }
            
            }else if($child ->getAttribute("DESCRIPTION")=='Review'){
                echo "<td class='reviews'>".$child ->getAttribute("ID")."</td>";
                echo "<td class='reviews'>".$child ->getAttribute("TIME")."</td>";
                echo "<td class='reviews'>".$child ->getAttribute("DESCRIPTION")."</td>";
                echo "</tr>";
                
                foreach ($child->childNodes as $secondChild){
                    $elements = $secondChild->nodeName;
                    
                    if($secondChild->nodeName=="HEADING"){
                        echo "<tr><td class='reviews' colspan='3'><h3>".$secondChild->nodeValue."</h3>";
                        
                    }else if($secondChild->nodeName=="STORY"){
                            echo "<div>";
                            
                            foreach($secondChild->childNodes as $thirdChild){
                                echo "<p>".$thirdChild->nodeValue."</p>";
                            
                            }
                        echo "</div></td></td></tr>";
                    }
                }
            }
            echo "</table></td>";    
        }
        echo "</tr>";
    }


    echo "</table>";
  
?>

</body>
</html>  