<?php
    require_once('db.php');
?>

<?php
    if(empty($_GET['Page'])){
        Header("Location: quiz.php?Page=1");
    }

    $sql = "SELECT ID, Question FROM questions";
    $result = $conn->query($sql);

    $AnswerAmount = $_GET['Page'];
    $AnswerNumMin = ($AnswerAmount - 1) * 3 + 1;
    $AnswerNumMax = $AnswerAmount * 3;
    $Count = 0;
    echo $AnswerNumMin;
    echo $AnswerNumMax;

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $Count++;
            if($Count >= $AnswerNumMin && $Count <= $AnswerNumMax){
                echo "====================== <br>";
                echo "id: " . $row["ID"]. " - Name: " . $row["Question"]. "<br>";
                $QID = $row['ID'];
                $sql2 = "SELECT ID, QID, Answer, Correct FROM answers WHERE QID = $QID";
                $result2 = $conn->query($sql2);
    
                if ($result2->num_rows > 0) {
                    // output data of each row
                    while($row2 = $result2->fetch_assoc()) {
                        echo "--------------------- <br>";
                        echo "ID: " . $row2["ID"]. " - QID: " . $row2["QID"]. " - Answer: " . $row2["Answer"]. " - Correct: " . $row2["Correct"]. "<br>";
                    }
                } 
                else {
                    echo "--------------------- <br>";
                    echo "0 results <br>";
                }
            }
        }
    } 
    else {
        echo "====================== <br>";
        echo "0 results <br>";
    }