<?php
$mysqli = new mysqli("localhost", "root", "p@ssw0rd", "adudimih_mazen");

// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
} else {
    $sql = "SELECT
  	a.id,
    a.barcode,
    a.EAN,
    a.artical_number,
    b.totalCount AS DUPLICATE
FROM
    items a
INNER JOIN(
    SELECT artical_number,
        COUNT(*) totalCount
    FROM
        items b
    GROUP BY
        artical_number
    HAVING
        COUNT(artical_number) > 1
) b
ON
    a.artical_number = b.artical_number
HAVING
    `EAN` = `barcode`
ORDER BY
    artical_number
DESC;";
    if ($result = $mysqli->query($sql)) { ?>
        <table>
            <tr>
                <td>id</td>
                <td>barcode</td>
                <td>artical_number</td>
                <td>EAN</td>
                <td>duplicate</td>
                <td>active</td>
            </tr>
            <?php
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($rows as $row) {
                $item_id = $row['id'];
                $sql1 = "SELECT id FROM transaction_items WHERE item_id = '$item_id'";
                $active = 'no';
                if ($result1 = $mysqli->query($sql1)) {
                    if ($result1->num_rows > 0) {
                        $active = 'yes';
                    }
                }
            ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['barcode']; ?></td>
                    <td><?php echo $row['artical_number']; ?></td>
                    <td><?php echo $row['EAN']; ?></td>
                    <td><?php echo $row['DUPLICATE']; ?></td>
                    <td><?php echo $active; ?></td>
                </tr>
    <?php
            }
        }
    }
    ?>
        </table>