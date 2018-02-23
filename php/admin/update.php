<?php 
    include_once '../config/config.php';
    $pdo = Database::getConnection();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Admin panel - Team update</title>
        <link rel="stylesheet" type="text/css" href="/css/style.css">
    </head>
    <body>
        <div class="">
        	<table class='table'>
        		<tr>
        			<th>Tournament</th>
        			<th>Category</th>
        			<th>Team's name</th>
        			<th>Number of participants</th>
        			<th></th>
        			<th>Edit</th>
        			<th>Delete</th>
        		</tr>
				<?php
	                $sql = "SELECT teams.teamname, tournaments.name, registers.id, registers.team_id, registers.category, registers.n_participants FROM teams, tournaments, registers WHERE teams.id = registers.team_id AND tournaments.id = registers.tournament_id";          
	                $query = $pdo->prepare ($sql);
	                $result = $query->execute ();

	                while ($current = $query->fetch (PDO::FETCH_ASSOC)):
	            ?>
	                <tr>
	                    <td><?= $current['name'] ?></td>
	                    <td><?= $current['category'] ?></td>
	                    <td><?= $current['teamname'] ?></td>
	                    <td><?= $current['n_participants'] ?></td>
	                    <td><a href="details.php?id=<?= $current['team_id'] ?>">Detalles</a></td>
	                    <td><a href="update.php?id=<?= $current['id'] ?>">Editar</a></td>
	                    <td><a href='delete.php?id=<?= $current['id'] ?>'>Borrar</a></td>
	                </tr>
	            <?php endwhile ?>
                
        	</table>
        </div>
    </body>
</html>
