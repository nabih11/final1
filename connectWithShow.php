<?php
$con=mysqli_connect("localhost","root","1234","team time orginazer project");
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
  $result = mysqli_query($con,"SELECT * FROM products");
echo "<table border='1'>
<tr>
<th>id</th>
<th>name</th>
 <th>phone</th>
 <th>age</th>
 <th>experience</th>
 <th>image</th>
</tr>";

 while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row['id'] . "</td>";
echo "<td>" . $row['name'] . "</td>";
echo "<td>" . $row['phone'] . "</td>";
echo "<td>" . $row['age'] . "</td>";
echo "<td>" . $row['experience'] . "</td>";
echo "<td>" . $row['image'] . "</td>";




echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>
