$row = mysqli_fetch_row($result);
				while ($row)
				{	
				   $announcement_id = $row[0];
				   $announcement_title = $row[1];
				   $announcement_description = $row[2];
				   $announcement_start_date = $row[3];
				   $announcement_end_date = $row[4];
				   $announcement_active = $row[5];
				   
			?>	
				<tr>
				  <td><a href="./Announcements.php?E=<?=$announcement_id?>"><i class="bi bi-pencil"></i></a></td>
				  <td><?=$announcement_title?></td>
				  <td><?=$announcement_description?></td>
				  <td><?=$announcement_start_date?></td>
				  <td><?=$announcement_end_date?></td>
				  <td><? if ($announcement_active==1) { ?><i class="bi bi-check-square"></i><? } else { ?><i class="bi bi-square"></i><? } ?></td>
				  <td><a href="./Announcements.php?D=<?=$announcement_id?>"><i class="bi bi-x-circle"></i></a></td>
				</tr>
			<?
					$row = mysqli_fetch_row($result);
				}  		
			mysqli_close($conn); 