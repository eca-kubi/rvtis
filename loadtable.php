<?php
include 'connect-sqlite.php';
extract($_GET);
if($query=='branch'){
  $rows =$pdo->query("SELECT b.idbranch, b.name branchname, b.iddistrict, d.idregion, d.longname district, r.longname region from branch b left join company c on b.idcompany=c.idcompany  left join district d on d.iddistrict=b.iddistrict left join region r on r.idregion=d.idregion where c.username='$companyusername' order by branchname")->fetchAll(PDO::FETCH_ASSOC);
  if($rows)
  echo json_encode($rows);
  else {echo json_encode(['success'=>false]);}
}
?>