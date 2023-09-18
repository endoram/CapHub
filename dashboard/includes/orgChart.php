<?php
require '../includes/config_m.php';

/* The code is checking if there are any records in the `cadet_coc` table for a specific `FQSN` value.
If there are records, it does nothing. If there are no records, it inserts a set of predefined
positions into the table for the given `FQSN` value. The code then closes the database connection
and echoes a closing `</div>` tag. */
echo '<div class="container-fluid">';
  $query = "SELECT * FROM cadet_coc WHERE FQSN='".$_SESSION['FQSN']."'";
  $result = $conn->query($query);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    }
    $conn->close();
  }
  else {
    $FQSN = $_SESSION['FQSN'];
    //echo "<script>alert('No Results Found');</script>";
    $positions = array('Cadet Commander', 'CDC for Support', 'Finance', 'Personnel', 'Safety', 'Supply', 'Web', 'CDC for Operations', 'First Sergeant', 'Leadership Officer', 'Leadership NCO', 'Aerospace Officer', 'Aerospace NCO', 'ES Officer', 'ES NCO');
    foreach ($positions as $key => $value) {
      $query = "INSERT INTO cadet_coc (FQSN, position) VALUES ('$FQSN', '$value')";
      $conn->query($query);
    }
    $conn->close();
  }
echo "</div>";
?>

<div class="container-fluid p-3">
  <pre class="mermaid">
    <?
    require '../includes/config_m.php';
    /* The code is executing a SQL query to retrieve data from the `cadet_coc` table and the
    `sq_members` table. It selects the `first_name`, `last_name`, `position`, `flight_name`, and
    `FQSN` columns from the `cadet_coc` table and joins it with the `sq_members` table on the
    `cap_id` column. The query filters the results based on the `FQSN` value stored in the
    `` variable. */
    $query = "SELECT first_name, last_name, position, cadet_coc.flight_name, cadet_coc.FQSN FROM cadet_coc LEFT JOIN sq_members on cadet_coc.cap_id = sq_members.cap_id WHERE cadet_coc.FQSN='".$_SESSION['FQSN']."' ORDER BY cadet_coc.id ASC";
    #echo $query;
    $result = $conn->query($query);

    $y = 0;
    $x = 0;
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $position[$x] = $row['flight_name']." ".$row['position'];
        $name1 = $row['first_name'] ." ". $row['last_name'];
        if(strlen($name1) <= 3) {
          $name1 = "Empty";
        }
        $names[$x] = $name1;
        $x = $x + 1;
      }
    }
      echo "flowchart TD
          A[".$position[0] .'\n'. " <b>$names[0]</b>]

          A --> 1[".$position[1].'\n'. " <b>$names[1]</b>]
          1 --> 8[".$position[2].'\n'. " <b>$names[2]</b>]
          1 --> 9[".$position[3].'\n'. " <b>$names[3]</b>]
          1 --> 10[".$position[4].'\n'. " <b>$names[4]</b>]
          1 --> 11[".$position[5].'\n'. " <b>$names[5]</b>]
          1 --> 12[".$position[6].'\n'. " <b>$names[6]</b>]

          A --> C[".$position[7].'\n'. " <b>$names[7]</b>] 

          A --> D[".$position[8].'\n'. " <b>$names[8]</b>]
          
          1 --> 2[".$position[9].'\n'. " <b>$names[9]</b>]
          2 --> 3[".$position[10].'\n'. " <b>$names[10]</b>]

          1 --> 4[".$position[11].'\n'. " <b>$names[11]</b>]
          4 --> 5[".$position[12].'\n'. " <b>$names[12]</b>]

          1 --> 6[".$position[13].'\n'. " <b>$names[13]</b>]
          6 --> 7[".$position[14].'\n'. " <b>$names[14]</b>]
          ";

      $result = $conn->query($query);
      $x = 0;
      /* This code block is iterating over the rows returned by the SQL query and storing the values of
      the `flight_name`, `position`, `first_name`, and `last_name` columns in the `` and
      `` arrays. */
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $position[$x] = $row['flight_name']." ".$row['position'];
          $name1 = $row['first_name'] ." ". $row['last_name'];
          if(strlen($name1) <= 3) {
            $name1 = "Empty";
          }
          $names[$x] = $name1;
          
          if ($x >= 15) {
            if ($x % 2 != 0) {
              echo "C --> ".$x."[".$position[$x].'\n'. " <b>$names[$x]</b>];";
              
            } else {
              $y = $x - 1;
              echo "$y --> ".$x."[".$position[$x].'\n'. " <b>$names[$x]</b>];";
            }
          }
          $x = $x + 1;
        }
        $conn->close();
      }
    else {
      echo "NO Results";
      $conn->close();
    }
    ?>
  </pre>

  <!-- The `<script type="module">` block is importing the `mermaid` library from a specific URL and
  initializing it with the `startOnLoad` option set to `true`. This allows the `mermaid` library to
  be used in the current script and automatically starts rendering the flowchart when the page
  loads. -->
  <script type="module">
    import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
    mermaid.initialize({ startOnLoad: true });
  </script>
</div>