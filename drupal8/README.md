Database 
# Backup your databases
docker exec -t your-db-container pg_dumpall -c -U postgres > your_dump.sql

# Restore your databases
cat your_dump.sql | docker exec -i your-db-container psql -U postgres

# แก้ไขการแสดง datetime ผิดกรณีเรา set from programming >>>>  core / modules / datetime
https://www.drupal.org/project/drupal/issues/2993165#comment-12767598
เข้าไปทีหน้า /admin/config/regional/settings  > TIME ZONES > Users may set their own time zone  > จะแสดงเวลาที่ถูกต้อง *** แต่เวลาในระบบ admin จะแสดงไม่ตรงนะ


# Reduce memory consumption of the functions using taxonomy loadTree
https://www.drupal.org/files/issues/2018-05-14/2183565-28.patch


# ลบ yeekee_answer ออกทั้งหมด
use Drupal\paragraphs\Entity\Paragraph;
$yeekee_answer = \Drupal\config_pages\Entity\ConfigPages::config('yeekee_answer');

foreach ($yeekee_answer->get('field_answer_yk')->getValue() as $ii=>$vv){

  $p = Paragraph::load( $vv['target_id'] );
  $round_target_id = $p->get('field_round_ye')->target_id;
  
  if(strcmp($round_target_id, 101) == 0){
    dpm( $round_target_id );
  }
  if ($p) $p->delete();
}

$yeekee_answer->set('field_answer_yk', $answer_yks);
$yeekee_answer->save();

// dpm( $terms  );
# ลบ yeekee_answer ออกทั้งหมด
