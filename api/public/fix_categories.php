<?php
$pdo = new PDO('mysql:host=localhost;dbname=qnztnquh_uberhealth', 'qnztnquh_uberhdb', 'Uber@Health2026!');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$n = $pdo->exec("
  UPDATE lessons SET category='decision', updated_at=NOW()
  WHERE slug IN (
    'branching-decision-trees',
    'scenario-branching-football-sunday',
    'scenario-branching-stress-cascade',
    'scenario-branching-when-a-branch-fails',
    'scenario-branching-with-therapist',
    'scenario-branching-after-relapse'
  )
");

$rows = $pdo->query("SELECT id, slug, category FROM lessons WHERE category='decision' ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['moved' => $n, 'lessons' => $rows]);
unlink(__FILE__);
