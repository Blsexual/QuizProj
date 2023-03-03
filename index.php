<?php
    require_once('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizzar</title>
</head>
<body>
<?php
 echo '<a href="quiz.php?Page=1">
      <input type="submit" value="To Quiz"/>
  </a>';
?>
    <form method="POST" action="index.php" id="QnA">
        <input type="hidden" name="NewQuestion" value="True">
        <label for="Question">Question:</label><br>
        <input type="text" id="Question" name="Question" required><br>

        <div id="Ans1">
            <label for="Answer1">Answer 1:</label><br>
            <input type="text" id="Answer1" name="Answer1"required><input type="radio" name="Correct"value="1"required><br>
        </div>
        <div id="Ans2">
            <label for="Answer2">Answer 2:</label><br>
            <input type="text" id="Answer2" name="Answer2"required><input type="radio" name="Correct"value="2"required><br>
        </div>
        <input type="hidden" id="amount" name="Amount">
        <input type="submit" value="Create" name="BtnCreate" id="submit">
    </form>
    <button onclick="duplicateAnswer()" id="newDiv">Duplicate Answer</button>
</body>
</html>

<?php
    if(!empty($_POST)){
        print_r($_POST);
        echo"<br>";
        if(!empty($_POST['NewQuestion'])){
            $stmt = $conn->prepare("INSERT INTO questions (`Question`) VALUES (?)");
            $stmt->bind_param("s", $question);

            // set parameters and execute
            $question = $_POST['Question'];
            $stmt->execute();
            $last_id = $conn->insert_id;

            if(!empty($_POST['Amount'])){
                $amount = $_POST['Amount'];
                echo $amount;
            }
            else{
                $amount = 2;
                echo $amount;
            }

            for($i=1;$i<$amount+1;$i++){
                $ans = "Answer".$i;
                $stmt = $conn->prepare("INSERT INTO answers (`QID`,`Answer`,`Correct`) VALUES (?,?,?)");
                $stmt->bind_param("isi", $last_id, $answer, $correct);
    
                // set parameters and execute
                $answer = $_POST[$ans];
                if($_POST['Correct'] == $i){
                    $correct = 1;
                }
                else{
                    $correct = 0;
                }
                $stmt->execute();
            }
            
        }
    }
    $sql = "SELECT ID, Question FROM questions";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
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
    else {
        echo "====================== <br>";
        echo "0 results <br>";
    }
    
?>
<script>
var count = 2;
var newDivBtn = document.getElementById("newDiv");

var submitButton = document.getElementById('submit');

function duplicateAnswer() {
  // Get a reference to the last div
  var lastDiv = document.getElementById('Ans' + count);

  // Create a new div element
  var newDiv = document.createElement('div');

  // Set the ID of the new div to "AnsX", where X is the next count value
  count++;
  if(count == 10){
    newDivBtn.disabled = true;
  }
  newDiv.id = 'Ans' + count;

  // Create the label element for the new div
  var newLabel = document.createElement('label');
  newLabel.setAttribute('for', 'Answer' + count);
  newLabel.textContent = 'Answer ' + count + ':';

  // Create the input element for the new div
  var newInput = document.createElement('input');
  newInput.setAttribute('type', 'text');
  newInput.setAttribute('id', 'Answer' + count);
  newInput.setAttribute('name', 'Answer' + count);

  // Create the radio button for the new div
  var newRadio = document.createElement('input');
  newRadio.setAttribute('type', 'radio');
  newRadio.setAttribute('name', 'Correct');
  newRadio.setAttribute('value', count.toString());
  newRadio.required = true;

  // Append the label, input, and radio button elements to the new div
  newDiv.appendChild(newLabel);
  newDiv.appendChild(document.createElement('br'));
  newDiv.appendChild(newInput);
  newDiv.appendChild(newRadio);
  newDiv.appendChild(document.createElement('br'));

  // Insert the new div after the last div, but before the submit button
  lastDiv.parentNode.insertBefore(newDiv, submitButton);

  var amout= document.getElementById("amount");
  amout.value = count;
}
</script>



