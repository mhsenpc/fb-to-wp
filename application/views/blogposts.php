<h2>Manage Blog Posts</h2>
<br />
<table>
<tr>
	<td>Title</td>
    <td>Content</td>
    <td>Publish Date</td>
    <td></td>
</tr>
<?php
  while($record=mysql_fetch_assoc($posts))
  {
?>
<tr>
	<td><?php echo $record['post_title']; ?></td>
    <td><?php echo $record['post_content']; ?></td>
    <td><?php echo $record['post_date']; ?></td>
    <td><a href="blogposts/Remove/<?php echo $record['ID']; ?>" class="btn btn-danger" >Delete</a></td>
</tr>
<?php } ?>
</table>