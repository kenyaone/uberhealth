<?php
// One-time lesson seeder — delete after running
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (\App\Models\Lesson::count() > 0) {
    echo "<pre>Lessons already seeded (" . \App\Models\Lesson::count() . " exist). Done.</pre>";
    exit;
}

$lessons = [
  [
    'title'            => 'Why Saying No Feels Hard',
    'slug'             => 'why-saying-no-feels-hard',
    'category'         => 'alcohol',
    'level'            => 'beginner',
    'duration_minutes' => 8,
    'order'            => 1,
    'thumbnail_emoji'  => '🛑',
    'summary'          => 'Understand the social and emotional forces that make refusal difficult — and why that is completely normal.',
    'key_takeaways'    => json_encode(['Fear of judgment, habit, and social pressure are the three main reasons refusal is hard.','Refusal is a skill, not a character trait — it gets easier with practice.','You do not owe anyone an explanation. "No thank you" is a complete sentence.']),
    'content'          => '<h2>Why is saying no so hard?</h2><p>Most people in recovery find that refusing a drink, a bet, or a cigarette is not just a physical act — it is an emotional and social one. We are wired to seek approval, avoid conflict, and belong to groups. Saying no can feel like saying "I reject you." That discomfort is real, and it is not weakness.</p><h3>The three main reasons refusal is hard</h3><ul><li><strong>Fear of judgment:</strong> They will think I am boring or antisocial.</li><li><strong>Habit and automatic behaviour:</strong> In some situations, using was automatic. Your brain still expects that pattern.</li><li><strong>Social pressure:</strong> When everyone around you is doing it, saying no stands out.</li></ul><h3>What research shows</h3><p>A 2019 study of alcohol refusal found that the most effective refusals were brief, confident, and did not require explanation. You do not owe anyone a reason. "No thank you" is a complete sentence.</p><h3>Your first step</h3><p>This week, practice saying no to something small and low-stakes — a food you do not want, a task you cannot take on. Notice how it feels. That muscle needs exercise before you need it under pressure.</p><blockquote>Recovery tip: Refusal is a skill, not a character trait. It gets easier every time you use it.</blockquote>',
    'is_premium'       => true,
  ],
  [
    'title'            => 'The STOP Technique',
    'slug'             => 'the-stop-technique',
    'category'         => 'alcohol',
    'level'            => 'beginner',
    'duration_minutes' => 10,
    'order'            => 2,
    'thumbnail_emoji'  => '✋',
    'summary'          => 'A four-step method to create space between a trigger and your response — giving you time to choose.',
    'key_takeaways'    => json_encode(['Stop. Take a breath. Observe. Proceed mindfully.','Breathing activates the parasympathetic nervous system and reduces cravings within seconds.','Practice STOP twice a day on small decisions until it becomes automatic.']),
    'content'          => '<h2>The STOP Technique</h2><p>The moment someone offers you a substance or temptation, your brain moves fast. STOP creates a 30-second pause — enough to interrupt the automatic response.</p><h3>S — Stop</h3><p>Literally pause. Do not respond immediately. Your brain needs only a few seconds to shift from reactive to thoughtful mode.</p><h3>T — Take a breath</h3><p>Breathe in for 4 counts, out for 4 counts. Cortisol (the stress hormone that drives craving) begins to drop within seconds.</p><h3>O — Observe</h3><p>Notice what is happening. What triggered this moment? You are not judging yourself — just watching.</p><h3>P — Proceed mindfully</h3><p>Now respond from a conscious choice, not a reflex. Short responses are powerful: "No thanks," "I will pass," "I am good."</p><blockquote>Practice STOP twice a day on small decisions. It becomes automatic when you need it most.</blockquote>',
    'is_premium'       => true,
  ],
  [
    'title'            => 'Assertive Refusal Phrases',
    'slug'             => 'assertive-refusal-phrases',
    'category'         => 'alcohol',
    'level'            => 'intermediate',
    'duration_minutes' => 12,
    'order'            => 3,
    'thumbnail_emoji'  => '💬',
    'summary'          => 'A toolkit of tested refusal phrases for different social situations — from casual offers to persistent pressure.',
    'key_takeaways'    => json_encode(['Effective refusals are brief, confident, and do not over-explain.','Use three tiers: simple refusal, firm refusal, then exit statement.','Body language carries 55% of the message — stand straight and make eye contact.']),
    'content'          => '<h2>Words that work</h2><p>The most successful refusal phrases are brief, confident, and do not over-explain. The more you explain, the more you invite debate.</p><h3>Tier 1 — Simple refusals</h3><ul><li>"No thanks."</li><li>"I am good, thank you."</li><li>"Not tonight."</li><li>"I will skip this round."</li></ul><h3>Tier 2 — If they push</h3><ul><li>"I said no thanks — I mean it."</li><li>"I do not drink/bet/smoke anymore."</li><li>"It is not for me."</li></ul><h3>Tier 3 — If they persist</h3><ul><li>"If you keep asking, I will need to leave."</li><li>"I need you to respect this."</li></ul><h3>In Kiswahili</h3><ul><li>"Hapana, asante." (No thank you.)</li><li>"Mimi si mtu wa kunywa." (I do not drink.)</li><li>"Niache tafadhali." (Please leave me.)</li></ul><blockquote>Role-play these phrases out loud. The first time you say them in real life should not be the first time your mouth has formed the words.</blockquote>',
    'is_premium'       => true,
  ],
  [
    'title'            => 'Handling Persistent Pressure',
    'slug'             => 'handling-persistent-pressure',
    'category'         => 'alcohol',
    'level'            => 'intermediate',
    'duration_minutes' => 15,
    'order'            => 4,
    'thumbnail_emoji'  => '🧱',
    'summary'          => 'What to do when one "no" is not enough — managing social pressure from friends, family, and colleagues.',
    'key_takeaways'    => json_encode(['The broken record technique: repeat your refusal without adding new reasons.','Plan your exit before you arrive at high-risk situations.','You do not need to justify your recovery to anyone.']),
    'content'          => '<h2>When no does not end the conversation</h2><p>Some people will not accept your refusal on the first try. Your job is not to convince them; it is to hold your decision.</p><h3>The broken record technique</h3><p>Repeat your refusal calmly and consistently, without changing your words or adding new reasons. Each time they push, you give the same response: "No thanks." Again: "No thanks." People who pressure you are looking for engagement, not a wall. Be the wall.</p><h3>The exit strategy</h3><p>Plan your exit from high-risk situations before you arrive. Drive your own car so you can leave. Have a prepared "I have an early morning" exit line. Text a sober friend your location and a check-in time.</p><h3>Recognising manipulation tactics</h3><ul><li>"Just this once" — There is no just once in recovery for many people.</li><li>"You are no fun anymore" — Fun is not defined by substances.</li><li>"It is a special occasion" — Every occasion becomes special when someone wants you to use.</li></ul><blockquote>After a high-pressure situation, debrief with your therapist or support group.</blockquote>',
    'is_premium'       => true,
  ],
  [
    'title'            => 'Branching: Decision Trees for Recovery',
    'slug'             => 'branching-decision-trees',
    'category'         => 'alcohol',
    'level'            => 'intermediate',
    'duration_minutes' => 12,
    'order'            => 5,
    'thumbnail_emoji'  => '🌿',
    'summary'          => 'Map your decision paths in advance so you already know what to do at every fork before you reach it.',
    'key_takeaways'    => json_encode(['Branching maps healthy decision paths before you face the moment of choice.','Build a personal decision tree for your top triggers.','Debrief almost-said-yes moments with your therapist.']),
    'content'          => '<h2>What is branching?</h2><p>Branching is a CBT technique where you map out decision paths in advance. Instead of facing a choice in the heat of the moment, you have already decided what to do at every fork.</p><h3>The basic decision tree</h3><p><strong>Trigger</strong> → Strong urge + alone → Call sober support<br><strong>Trigger</strong> → Strong urge + in company → Use STOP + refusal phrase → if pushed, exit<br><strong>Trigger</strong> → Mild urge → Acknowledge: "I notice a craving. It will pass." → Walk, water, phone a friend</p><h3>Building your personal tree</h3><p>Common triggers include: stress at work, arguments at home, boredom, loneliness, certain locations, certain people, and celebrations. For each trigger, write your branch. For example: Trigger: Stressful day at work. Old branch: Buy alcohol on the way home. New branch: Call therapist → go to gym → cook dinner.</p><h3>The almost-said-yes debrief</h3><p>If you almost gave in, ask: What was the trigger? What thought came first? What did I do at the branch point? What can I do differently next time? Write it down and bring it to your next session.</p><blockquote>The goal is not a perfect tree — it is a practiced one.</blockquote>',
    'is_premium'       => true,
  ],
  [
    'title'            => 'Surfing the Urge',
    'slug'             => 'surfing-the-urge',
    'category'         => 'alcohol',
    'level'            => 'intermediate',
    'duration_minutes' => 10,
    'order'            => 6,
    'thumbnail_emoji'  => '🏄',
    'summary'          => 'Observe cravings as waves and let them pass without being carried away.',
    'key_takeaways'    => json_encode(['Cravings peak at 15-30 minutes then decline whether or not you act on them.','Surfing means observing the urge, not fighting it.','Every craving you surf makes the next one easier.']),
    'content'          => '<h2>Cravings are temporary</h2><p>Research shows that cravings peak between 15 and 30 minutes and then decline — whether or not you act on them. Urge surfing is the skill of staying with that wave without acting, knowing it will break.</p><h3>The surfing technique</h3><ol><li>Notice the urge without judgement: "There it is. A craving."</li><li>Locate it in your body. Where do you feel it? Chest? Stomach? Hands?</li><li>Watch it intensify. It will get stronger before it gets weaker. This is normal.</li><li>Breathe through the peak. Long slow breaths.</li><li>Notice the decline. The craving weakens.</li></ol><h3>Why fighting makes it worse</h3><p>Trying to suppress a craving often amplifies it. Surfing works because you are not fighting; you are observing.</p><blockquote>Every craving you surf successfully makes the next one easier. Your brain is learning a new pattern.</blockquote>',
    'is_premium'       => true,
  ],
  [
    'title'            => 'Identifying Your Triggers',
    'slug'             => 'identifying-your-triggers',
    'category'         => 'wellness',
    'level'            => 'beginner',
    'duration_minutes' => 10,
    'order'            => 7,
    'thumbnail_emoji'  => '🎯',
    'summary'          => 'Map the people, places, emotions, and times that increase your vulnerability to relapse.',
    'key_takeaways'    => json_encode(['HALT: Hungry, Angry, Lonely, Tired — the four most common internal triggers.','External triggers include specific people, places, times, and sensory cues.','A personal trigger map shared with your therapist becomes a working recovery tool.']),
    'content'          => '<h2>What is a trigger?</h2><p>A trigger is anything — internal or external — that activates craving or increases your likelihood of using. Triggers are not causes; they are signals. Understanding them is the first step to managing them.</p><h3>The HALT framework</h3><p>The most common internal triggers: Hungry, Angry, Lonely, Tired. Before a high-risk situation, ask: Am I HALT? Address each one before you go.</p><h3>External triggers</h3><ul><li>Specific people (using friends, suppliers, certain family members)</li><li>Specific places (old bars, betting shops, certain routes home)</li><li>Times of day (after work, Friday evenings, paydays)</li><li>Sensory cues (smell of alcohol, sound of casino music)</li></ul><h3>Building your personal trigger map</h3><p>List your top 5 external triggers and top 3 internal triggers. For each one, write a brief plan: "When [trigger], I will [response]."</p><blockquote>Share your trigger map with your therapist. It becomes a working document for your recovery plan.</blockquote>',
    'is_premium'       => true,
  ],
  [
    'title'            => 'The 5-4-3-2-1 Grounding Technique',
    'slug'             => '5-4-3-2-1-grounding',
    'category'         => 'wellness',
    'level'            => 'beginner',
    'duration_minutes' => 8,
    'order'            => 8,
    'thumbnail_emoji'  => '🌍',
    'summary'          => 'Use your five senses to anchor yourself in the present moment during anxiety or craving episodes.',
    'key_takeaways'    => json_encode(['Name 5 things you see, 4 you touch, 3 you hear, 2 you smell, 1 you taste.','Grounding re-engages the prefrontal cortex and reduces the amygdala threat response.','Takes less than 2 minutes — do it anywhere.']),
    'content'          => '<h2>When your mind races, come back to your senses</h2><p>Grounding techniques interrupt the anxiety spiral or craving cascade by redirecting your attention to the physical present.</p><h3>How to do it</h3><ul><li><strong>5 things you can SEE</strong> — Name them. A chair. A window. Your hands. The colour of the wall. A cup.</li><li><strong>4 things you can TOUCH</strong> — Your clothes against your skin. The floor under your feet. The hardness of the table.</li><li><strong>3 things you can HEAR</strong> — A bird. Traffic. Your own breathing.</li><li><strong>2 things you can SMELL</strong> — If you cannot smell anything, recall a smell you love.</li><li><strong>1 thing you can TASTE</strong> — The inside of your mouth. A sip of water.</li></ul><h3>Why it works</h3><p>Sensory grounding re-engages the prefrontal cortex — the decision-making part of the brain — by giving it concrete, present-moment data to process.</p><blockquote>Save this in your phone notes so you have it when you need it most.</blockquote>',
    'is_premium'       => true,
  ],
  [
    'title'            => 'Understanding Depression',
    'slug'             => 'understanding-depression',
    'category'         => 'depression',
    'level'            => 'beginner',
    'duration_minutes' => 12,
    'order'            => 9,
    'thumbnail_emoji'  => '🌧️',
    'summary'          => 'What depression actually is, how it differs from sadness, and why it is not a choice or character flaw.',
    'key_takeaways'    => json_encode(['Depression is a medical condition, not sadness or weakness.','In Kenya, depression often presents through physical symptoms like pain and fatigue.','80% of people with depression respond well to treatment.']),
    'content'          => '<h2>Depression is not just sadness</h2><p>Sadness is a normal emotion triggered by loss or disappointment. Depression is a medical condition in which brain chemistry, sleep, energy, thinking, and mood are affected persistently — often without an obvious cause.</p><h3>Key symptoms</h3><ul><li>Persistent low mood lasting more than 2 weeks</li><li>Loss of interest in things you used to enjoy</li><li>Fatigue even after rest</li><li>Difficulty concentrating or making decisions</li><li>Changes in appetite or weight</li><li>Feelings of worthlessness or guilt</li><li>In severe cases: thoughts of death or self-harm</li></ul><h3>Depression in Kenya</h3><p>In Kenya, depression often goes undiagnosed because it is frequently expressed through physical symptoms — persistent body pain, headaches, digestive problems — rather than "sad feelings." Many people say "I am tired" when they mean "I am depressed."</p><h3>It is treatable</h3><p>80% of people with depression respond well to treatment — therapy, medication, or both. Seeking help is the most courageous thing you can do.</p><blockquote>If you scored Moderate or above on your PHQ-9, please book a session with one of our therapists today.</blockquote>',
    'is_premium'       => false,
  ],
  [
    'title'            => 'Managing Anxiety: Breathing Exercises',
    'slug'             => 'managing-anxiety-breathing',
    'category'         => 'anxiety',
    'level'            => 'beginner',
    'duration_minutes' => 10,
    'order'            => 10,
    'thumbnail_emoji'  => '💨',
    'summary'          => 'Three evidence-based breathing techniques to reduce anxiety symptoms quickly.',
    'key_takeaways'    => json_encode(['Box breathing (4-4-4-4) takes 64 seconds and is used by surgeons and special forces.','Extended exhale (4-7-8) triggers the strongest parasympathetic response.','2 minutes of box breathing every morning reduces baseline anxiety over time.']),
    'content'          => '<h2>Why breathing works for anxiety</h2><p>Slow, controlled breathing directly activates the parasympathetic nervous system, reversing fight-or-flight physiological changes within minutes.</p><h3>Box Breathing (4-4-4-4)</h3><ol><li>Breathe in for 4 counts</li><li>Hold for 4 counts</li><li>Breathe out for 4 counts</li><li>Hold for 4 counts</li></ol><p>Repeat 4 times. Takes 64 seconds.</p><h3>Extended Exhale (4-7-8)</h3><ol><li>In through nose for 4 counts</li><li>Hold for 7 counts</li><li>Out through mouth for 8 counts</li></ol><p>Do not do more than 4 cycles at first.</p><h3>Belly Breathing</h3><p>Put one hand on your chest, one on your belly. Breathe in so that only your belly hand moves. Training belly breathing retrains your default breathing pattern away from anxious shallow chest breathing.</p><blockquote>Set a phone reminder for 7am: 2 minutes of box breathing. It takes less time than checking social media.</blockquote>',
    'is_premium'       => false,
  ],
  [
    'title'            => 'Introduction to Mindfulness for Recovery',
    'slug'             => 'mindfulness-for-recovery',
    'category'         => 'wellness',
    'level'            => 'beginner',
    'duration_minutes' => 12,
    'order'            => 11,
    'thumbnail_emoji'  => '🧘',
    'summary'          => 'How mindfulness practice supports addiction recovery — and a simple daily routine to get started.',
    'key_takeaways'    => json_encode(['Mindfulness creates a gap between urge and action.','8 weeks of mindfulness training reduced alcohol craving by 27% in research.','The busy mind you bring to practice is the material — not a failure.']),
    'content'          => '<h2>What mindfulness means in recovery</h2><p>Mindfulness is the practice of paying attention to the present moment without judgement. In recovery, it creates a gap between your thoughts or urges and your actions.</p><h3>The science</h3><p>A 2014 study found that 8 weeks of mindfulness training reduced alcohol craving by 27% and substance use by 19% compared to a control group. The mechanism: mindfulness strengthens the prefrontal cortex and reduces amygdala reactivity.</p><h3>A simple daily practice (5 minutes)</h3><ol><li>Sit comfortably.</li><li>Close your eyes or lower your gaze.</li><li>Breathe naturally.</li><li>When a thought comes, notice it: "Thinking about work." Then return to breath.</li><li>When a feeling comes, notice it: "Feeling anxious." Then return to breath.</li><li>Repeat for 5 minutes.</li></ol><h3>Mindfulness is not emptying your mind</h3><p>Your mind will wander 50 times in 5 minutes. That is normal. Returning is the practice.</p><blockquote>Try the Insight Timer app (free) for guided sessions in English and Kiswahili.</blockquote>',
    'is_premium'       => true,
  ],
];

echo "<pre>";
$count = 0;
foreach ($lessons as $l) {
    \App\Models\Lesson::create(array_merge($l, ['is_published' => true]));
    echo "OK: " . $l['title'] . "\n";
    $count++;
}
echo "\nSeeded $count lessons successfully.\n</pre>";
