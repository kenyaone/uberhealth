<?php
$pdo = new PDO('mysql:host=localhost;dbname=qnztnquh_uberhealth', 'qnztnquh_uberhdb', 'Uber@Health2026!');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$lessons = [];

// ── Scenario 1: Sunday Football ──────────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-branching-football-sunday';
$l['title']    = 'Branching in Practice: Football Sunday';
$l['title_sw'] = 'Matawi Katika Vitendo: Jumapili ya Mpira';
$l['category'] = 'alcohol';
$l['language'] = 'en';
$l['level']    = 'beginner';
$l['duration_minutes'] = 9;
$l['thumbnail_emoji']  = '⚽';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 3;
$l['summary']    = 'Peter maps his decision tree the night before football Sunday — every branch, every phrase, every exit — then we watch him execute it in real time.';
$l['summary_sw'] = 'Peter anaweka ramani ya mti wake wa maamuzi usiku mmoja kabla ya Jumapili ya mpira — kila tawi, kila msemo, kila kutoka — kisha tunamwona akitekeleza kwa wakati halisi.';
$l['content'] = <<<'EOT'
## Background

Peter is 29. He has been in alcohol recovery for three months. Every Sunday since he was 22, he has watched football with the same group of six men at a local joint in Kasarani. Beer is inseparable from this ritual — for them. They are good friends and they do not know he stopped.

This Sunday is a big match. Everyone will be there. He has not missed a Sunday in seven years.

This is his most recurring high-risk scenario. He decides to build a decision tree the night before.

---

## Saturday night: Building the tree

He sits at his desk with a pen and paper. He starts from the trigger — walking into the joint — and maps forward.

**Peter's decision tree for Football Sunday:**

```
TRIGGER: Arrive at the joint

  Is a drink already on the table / handed to me?
  |
  YES ──> Push it gently aside. Gesture to waiter.
  |       Phrase: "Niletee maji kwanza." (Bring me water first.)
  |       [Go to: ROUND ORDERED]
  |
  NO ──> Sit. Get comfortable. Wait for waiter.
         Order soda water with lime before anyone orders for you.
         [Go to: SETTLED IN]

ROUND ORDERED (first round):
  Is someone ordering for the table including me?
  |
  YES ──> "Yangu ni baridi, hii." (Mine is a cold one, this.)
  |        Hold up the soda water / wave at your own glass.
  |        Most people will not look closely.
  |
  NO ──> Order your own drink quietly. No announcement.

SETTLED IN (mid-match):
  Do I feel the pull?
  |
  YES ──> Run the STOP sequence. 4 counts in, 4 out.
  |       Text support person: "Niko. Pull ipo." (I am here. Pull is there.)
  |       Redirect attention back to the match. Comment loudly about the game.
  |
  NO ──> Check in with yourself every 30 minutes. If YES arrives, go above.

DIRECT QUESTION ("Kwani hujanywa leo?"):
  Is it casual / joking?
  |
  YES ──> "Nimechukua break kidogo." + redirect to the match.
           "Ooh ile foul ilikuwa ya kweli." (Oh that foul was real.)
  |
  NO / Persistent ──>
           "Kweli bro, niko sawa. Hebu tuangalie hii."
           (Really bro, I am fine. Let us watch this.)
           Broken record if continued.

EXIT TRIGGER:
  If the pull is a 7 out of 10 or above AND I have been there over 90 minutes:
  ──> "Watu lazima niende — iko kitu kidogo."
      Hug the nearest person. Leave immediately.
      Text support person from the car.
```

He reads it back. Adjusts one phrase. Folds the paper and puts it in his pocket.

He will carry it to the joint tomorrow.

---

## Sunday: The real event

**12:10 PM — Arrival**

He walks in. Before he even reaches the table, James — the loudest in the group — is already standing, reaching for the counter.

*"Peter! Unakuja time nzuri. Naagiza sasa hivi."*
(Peter! You are arriving at a good time. I am ordering right now.)

Peter raises a hand: *"Nisubiri kidogo, niende choo kwanza."*
(Hold on a bit, let me go to the bathroom first.)

He goes to the bathroom. He takes the paper out of his pocket and reads the first branch again. Not because he has forgotten it — because reading it before the first challenge makes it more retrievable.

He comes back. He catches the waiter before James does and says quietly: *"Niletee maji na baridi moja tena."* The waiter nods. His drink arrives at the table at the same time as James's order.

Nobody notices.

**Branch used:** Pre-emption at arrival. He controlled what arrived in front of him before anyone else could.

---

**1:35 PM — The pull arrives**

First half, nil-nil. Boring match. The conversation has moved to work, then to gossip, then back to football. Peter has been comfortable. He is enjoying himself.

Then, for no reason he can identify precisely, the pull arrives. A familiar warmth-and-longing that has nothing to do with thirst. It is the feeling of the ritual — the smell of the room, the particular way his friends laugh, the memory of how this used to feel with a cold bottle in his hand.

He runs the STOP sequence without moving or changing his expression. Breath in, four counts. Out, four counts. He observes the feeling without moving toward it.

He takes out his phone and types under the table to his support person: *"Niko. Pull ipo. Minute 35."*

Reply comes in forty seconds: *"Noted. Unaenda lini?"* (When are you leaving?)

He replies: *"3 PM."* Putting the exit time in the message makes it more real.

He redirects. Loudly: *"Mbona mlinzi wa kwao anacheza vibaya hivyo?!"* (Why is their goalkeeper playing so badly like that?!)

The table erupts. The feeling begins to recede.

**Branch used:** STOP sequence + support contact text + active redirect.

---

**2:15 PM — The direct question**

The match is into the second half. Tony, who has known Peter since university, is relaxed and noticing things.

*"Bro, nikuangalie vizuri. Umekuwa unanywa nini leo?"*
(Bro, let me look at you carefully. What have you been drinking today?)

Peter looks at his glass. Soda water, mostly finished, a bit of lime at the bottom.

*"Nimechukua break kidogo. Dawa fulani. Hebu uangalie corner ile."*
(I have taken a small break. Some medication. Look at that corner kick.)

Tony: *"Oh serious? Uko sawa?"*
(Oh serious? Are you okay?)

*"Niko fiti kabisa, wororo tu. Ile corner iko offside!"*
(I am completely fine, just maintenance. That corner was offside!)

Tony shrugs and turns back to the screen.

**Branch used:** Prepared phrase ("break kidogo" + vague reason) + immediate redirect. Peter did not say he was in recovery. He was not ready for that conversation yet, and the football pitch gave him a redirect that Tony accepted immediately.

---

**3:00 PM — Exit**

He had told his support person 3 PM. It is 3 PM.

The match is still going, but it is winding down. He checks in with himself: the pull is around a 3 out of 10. Low. He could stay.

He leaves anyway. Not because he cannot handle it — because he said 3 PM, and keeping his own agreements is part of the practice.

*"Watu, ifikie hapa kwangu. Iko kitu afternoon."*
(People, this is my stop. I have something in the afternoon.)

Goodbyes. He is in his car four minutes later.

He texts his support person: *"Out. Clean. See you Thursday."*

---

## The debrief

That evening he opens his recovery journal:

*"Football Sunday done. Used: pre-emption at arrival, STOP at 1:35, support text, redirect twice. Tony asked — used break + medication + redirect. Left at 3 PM as planned. Pull was manageable. Will update tree: add branch for Tony specifically next time — he is the most observant."*

He adds one branch to his tree:

```
IF TONY ASKS DIRECTLY (he is observant — likely):
  "Nimechukua break. Kitu kidogo cha daktari." (I have taken a break. Small doctor thing.)
  Redirect to: specific match moment happening right now.
```

The tree is now more accurate than it was last night. Every time he uses it, it improves.

---

## What this scenario teaches about decision trees

**Build it the night before, not the morning of.** The night before, you are calm enough to think clearly. The morning of, anticipatory anxiety has already started.

**Carry the physical paper.** Reading it before the first challenge makes the branches more retrievable. It is not that you have forgotten — it is that retrieval under pressure is slower than retrieval when calm.

**Every exit time is a commitment, not a suggestion.** Peter could have stayed. He left anyway. Keeping the agreement with yourself — especially when you do not strictly need to — trains the part of your brain that makes agreements with itself believable.

**Update the tree after every use.** The version of the tree after three uses is more accurate than the version you built the first time. It is a living document, not a one-time plan.
EOT;
$l['content_sw'] = <<<'EOT'
## Muktadha

Peter ana miaka 29. Amekuwa katika kupona kutoka kwa pombe kwa miezi mitatu. Kila Jumapili tangu alikuwa na miaka 22, ameangalia mpira na kundi la wanaume sita katika sehemu ya karibu Kasarani. Pombe haiwezi kutenganishwa na ibada hii — kwao. Ni marafiki wazuri na hawajui ameacha.

## Jumamosi Usiku: Kujenga Mti

Peter anaketi mezani na kalamu na karatasi. Anaanza kutoka kwa kisababishi — kuingia ndani — na anaweka ramani mbele.

Anasoma nyuma. Anabadilisha msemo mmoja. Anakunja karatasi na kuiweka mfukoni.

Ataubeba kwenye sehemu kesho.

## Jumapili: Tukio Halisi

Peter alitumia: kuzuia mapema kufika, mfuatano wa STOP dakika 35, maandishi ya mtu wa msaada, kuelekeza mara mbili. Tony aliuliza — alitumia mapumziko + dawa + kuelekeza. Aliondoka saa 3 PM kama ilivyopangwa.

## Kile Hali Hii Inafundisha

Jenga usiku mmoja kabla, si asubuhi ya siku hiyo. Ubeba karatasi halisi. Kila wakati wa kuondoka ni ahadi, si pendekezo. Sasisha mti baada ya kila matumizi.
EOT;
$l['key_takeaways'] = json_encode([
    "Build the decision tree the night before — when you are calm enough to think clearly, not the morning when anticipatory anxiety has started.",
    "Carry the physical paper and re-read the first branch before the first challenge. Retrieval under pressure is slower than retrieval when calm.",
    "Pre-emption: control what drink arrives in front of you before anyone else can. This eliminates the most common pressure point.",
    "Every exit time is a commitment, not a suggestion. Leaving when you said you would — even when you could stay — trains your brain to trust its own agreements.",
    "Update the tree after every use. Add a specific branch for the most observant person in the group. The tree improves with each use."
]);
$l['key_takeaways_sw'] = json_encode([
    "Jenga mti usiku mmoja kabla — unapokuwa wa utulivu wa kutosha kufikiria wazi, si asubuhi wakati wasiwasi wa kutarajia umeanza.",
    "Beba karatasi halisi na usoma tena tawi la kwanza kabla ya changamoto ya kwanza.",
    "Kuzuia mapema: dhibiti kinywaji kinachokuja mbele yako kabla ya mtu mwingine yeyote.",
    "Kila wakati wa kuondoka ni ahadi, si pendekezo.",
    "Sasisha mti baada ya kila matumizi. Ongeza tawi maalum kwa mtu anayeona zaidi."
]);
$lessons[] = $l;

// ── Scenario 2: The Stress Cascade ───────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-branching-stress-cascade';
$l['title']    = 'Branching in Practice: When Stress Triggers You';
$l['title_sw'] = 'Matawi Katika Vitendo: Wakati Msongo Unaposababisha';
$l['category'] = 'alcohol';
$l['language'] = 'en';
$l['level']    = 'intermediate';
$l['duration_minutes'] = 8;
$l['thumbnail_emoji']  = '😤';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 4;
$l['summary']    = 'Diana does not have an event to prepare for — she has a bad day at work. Decision trees are not just for parties. See how she builds one for emotional triggers.';
$l['summary_sw'] = 'Diana hana tukio la kujiandaa — ana siku mbaya kazini. Miti ya maamuzi si kwa sherehe tu. Ona jinsi anavyojenga moja kwa visababishi vya kihisia.';
$l['content'] = <<<'EOT'
## Background

Diana is 33. She is seven months clean from alcohol. Her main triggers are not social — they are emotional. Specifically: being humiliated at work, feeling unheard, and the specific combination of exhaustion plus anger that she has learned, the hard way, produces her highest-risk state.

Her therapist has helped her identify this pattern: it is not the stressor that triggers her — it is the combination of stressor plus isolation. If the same bad day happens and she talks to someone within an hour, the risk drops dramatically.

She has built a decision tree not for an event, but for an emotional state.

---

## The tree she built three weeks ago

```
TRIGGER: I feel humiliated, dismissed, or disrespected by someone
         with authority over me (manager, client, family elder)

IMMEDIATE BRANCH (within 10 minutes):
  Am I alone?
  |
  YES ──> Do NOT go home yet. Go somewhere with people (café, matatu stage).
  |       Text support person: "Bad one. Call me when free."
  |
  NO ──> Tell someone near me in one sentence: "Nilikuwa na wakati mbaya."
          (I had a bad time.) I do not need them to fix it — I need to say it aloud.

1-HOUR BRANCH:
  Have I eaten in the last 4 hours?
  |
  NO ──> Eat something first. HALT check: Hungry, Angry, Lonely, Tired.
  |       If 2+ of these are true: HIGH ALERT. Contact support person immediately.
  |
  YES ──> Proceed to ENVIRONMENT CHECK.

ENVIRONMENT CHECK:
  Am I heading somewhere that has alcohol?
  |
  YES ──> Reroute. Take a different route home. Avoid the kiosk on Thika Road.
  |       If passing is unavoidable: walk on the far side of the road, phone in hand.
  |
  NO ──> Proceed home. Do not open the door without texting support person first:
          "Naingia nyumbani. Niko sawa." (I am going home. I am okay.)

AT HOME:
  Is the urge above 5/10?
  |
  YES ──> Do not be alone. Call support person. If no answer: go to a neighbour,
  |       walk to the nearest busy place, do NOT sit in silence.
  |
  NO ──> HALT routine: eat, short walk, shower, sleep by 10 PM.
```

She knows this tree by heart. She has used it four times in seven months. Each time it has worked.

---

## Wednesday: The bad day

Her manager contradicts her in front of the whole team in a 10 AM Zoom meeting — loudly, incorrectly, and without giving her a chance to respond. She sits with her camera on while her face is burning.

The meeting ends. It is 10:47 AM.

She is at the office. She is not alone. She tells the colleague next to her, in a low voice: *"Nilikuwa na wakati mbaya sana just now."*

Her colleague looks up: *"Oh — the Zoom? I saw that. That was not fair."*

That is enough. Diana nods. She does not need the whole conversation. She just needed to say it aloud once, to someone who witnessed it.

**Branch used:** Immediate branch — not alone, said it aloud. The act of externalising the experience in one sentence breaks the first stage of the stress cascade.

---

## 12:30 PM: The HALT check

She has not eaten since 7 AM. She checks the HALT criteria:
- **H — Hungry:** YES
- **A — Angry:** YES
- **L — Lonely:** Moderate
- **T — Tired:** Not yet

Two confirmed: HIGH ALERT. She texts her support person: *"Bad morning. H and A on HALT. Lunch now then checking in with you 2 PM?"*

She goes to a restaurant — not the kiosk. She orders a full meal. She eats slowly, facing the window.

The hunger resolves the worst edge of the anger. This is physiological, not willpower. Eating under stress activates the parasympathetic system. Her thinking clears slightly by the time she finishes.

**Branch used:** 1-hour HALT check. Two flags triggered the alert. Eating was the intervention, not willpower.

---

## 4:45 PM: The environment check

She is leaving the office. The most direct route home passes the wine shop on Ngong Road where she used to stop every Wednesday after work.

She checks the branch. The tree says: reroute. She takes the longer way, adding twelve minutes to her journey. She does not debate this. The branch says reroute, she reroutes.

She texts her support person from the matatu: *"Niko. Avoiding Ngong road route. ETA home 6."*

Her support person replies: *"Smart. Text me when you are inside."*

**Branch used:** Environment check — reroute.

---

## 6:08 PM: At the door

She is at her front door. The tree says: text before you go in.

She texts: *"Naingia nyumbani. Niko sawa."*

She gets a reply emoji. She goes in.

She checks her urge level: 3 out of 10. Low. Below the threshold that triggers the call-someone-immediately branch.

She runs the HALT routine: she heats leftover food, she takes a twenty-minute walk around the estate, she showers, she is in bed by 9:45 PM.

---

## The debrief

The next morning she writes in her journal:

*"Wednesday. Manager call-out. Bad one. Tree worked. Used: say it aloud at 10:47, HALT at 12:30 (H+A), full meal, rerouted Ngong road, texted before going in. Urge was 3/10 at the door. Slept well. One thing to update: between 2 PM and 5 PM at the office I was alone. That gap needs a branch."*

She adds to the tree:

```
OFFICE AFTERNOON (if trigger was in the morning):
  Check in with someone between 2 PM and 4 PM.
  It does not have to be about the trigger — any conversation breaks the isolation.
```

---

## What makes this tree different

Most people think of decision trees as tools for events — parties, family gatherings, places where alcohol is present. This is correct but incomplete.

Diana's highest-risk state is not a place. It is a combination of three conditions: bad news, no food, and isolation. Her tree is calibrated to interrupt that combination at the earliest possible stage — not at the door of the kiosk at 9 PM, but at 10:47 AM when she first felt it forming.

**The earlier in the cascade you interrupt it, the less force you need.**

At 10:47 AM, saying one sentence to a colleague required almost no willpower.
At 9 PM, standing outside the wine shop, it would have required everything she had.

The tree is designed to catch her at 10:47, not at 9 PM.

---

## Your version

Identify your emotional trigger combination. For most people it is not one thing — it is a pattern. Common ones:

- Rejection + alone
- Argument + exhausted
- Financial pressure + weekday evening
- Humiliation + the commute home

Map the earliest point in the cascade where you can intervene. Build your first branch there. That is the most valuable branch in the tree.
EOT;
$l['content_sw'] = <<<'EOT'
## Muktadha

Diana ana miaka 33. Ana miezi saba safi kutoka kwa pombe. Visababishi vyake vikuu si vya kijamii — ni vya kihisia. Hasa: kufedheheshwa kazini, kuhisi kutosikiwa, na mchanganyiko maalum wa uchovu pamoja na hasira.

Mti wake hauko kwa tukio, bali kwa hali ya kihisia.

## Kile Tofauti katika Mti Huu

Watu wengi wanafikiri miti ya maamuzi kama zana za matukio — sherehe, mikutano ya familia, mahali ambapo pombe ipo. Hii ni sahihi lakini haikamiliki.

Kisababishi kikuu cha Diana si mahali. Ni mchanganyiko wa masharti matatu: habari mbaya, bila chakula, na kutengwa.

**Mapema unavyoizuia kwenye cascade, nguvu ndogo unayohitaji.**

Saa 10:47 AM, kusema sentensi moja kwa mwenzake kulihitaji nguvu karibu si chochote.
Saa 9 PM, kusimama nje ya duka la divai, kungehitaji kila kitu alichokuwa nacho.

Mti umepangwa kumkamata saa 10:47, si saa 9 PM.
EOT;
$l['key_takeaways'] = json_encode([
    "Decision trees are not only for social events — build one for your emotional trigger pattern, not just for places.",
    "Identify your trigger combination: it is usually not one thing but a pattern (rejection + alone, argument + exhausted).",
    "HALT check: Hungry, Angry, Lonely, Tired. If 2+ are true simultaneously, that is HIGH ALERT — contact support immediately.",
    "The earlier in the cascade you interrupt, the less willpower you need. Build your first branch at the earliest visible warning sign.",
    "Rerouting (avoiding a high-risk environment) is a branch, not a failure. Add your specific routes and times to the tree."
]);
$l['key_takeaways_sw'] = json_encode([
    "Miti ya maamuzi si kwa matukio ya kijamii tu — jenga moja kwa mfumo wako wa kisababishi cha kihisia.",
    "Tambua mchanganyiko wako wa kisababishi: kawaida si kitu kimoja bali mfumo.",
    "Ukaguzi wa HALT: Njaa, Hasira, Upweke, Uchovu. Ikiwa 2+ ni kweli wakati mmoja, hiyo ni TAHADHARI KUBWA.",
    "Mapema unavyoizuia kwenye cascade, nguvu ndogo unayohitaji.",
    "Kupita njia nyingine (kuepuka mazingira ya hatari kubwa) ni tawi, si kushindwa."
]);
$lessons[] = $l;

// ── Scenario 3: When a Branch Fails ──────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-branching-when-a-branch-fails';
$l['title']    = 'Branching in Practice: When a Branch Fails';
$l['title_sw'] = 'Matawi Katika Vitendo: Wakati Tawi Linashindwa';
$l['category'] = 'alcohol';
$l['language'] = 'en';
$l['level']    = 'intermediate';
$l['duration_minutes'] = 9;
$l['thumbnail_emoji']  = '🔧';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 5;
$l['summary']    = 'The scenario nobody prepares for: your branch does not work. See how Marcus updates his tree in real time and what to do when the plan meets reality.';
$l['summary_sw'] = 'Hali ambayo hakuna anayejiandaa: tawi lako halifanyi kazi. Ona jinsi Marcus anavyosasisha mti wake kwa wakati halisi na kufanya nini mpango unapokutana na ukweli.';
$l['content'] = <<<'EOT'
## Background

Marcus is 41. He has been in recovery from alcohol for eleven months. He has used his decision tree successfully at four events this year. He feels confident going into his company's end-of-year party at a hotel in Upper Hill.

He has a tree. He has used trees before. He is not worried.

This will be the scenario that teaches him the most important lesson about decision trees.

---

## His tree going in

```
END-OF-YEAR PARTY — Decision Tree

Arrival:
  Order sparkling water at the bar. Always have it in hand.

First round:
  If ordered for: "Yangu iko hapa, asante." (Mine is here, thanks.)

Colleague pressure:
  Tier 1: "Niko sawa, asante."
  Tier 2 if pushed: "Nimechukua break."
  Broken record if continued.

Exit trigger:
  If pull exceeds 6/10 OR time reaches 9:30 PM — leave.
```

It looks complete. It has worked before. He feels prepared.

---

## What the tree did not account for

The party is at a hotel. Open bar. Passed canapés. Live music. And — the element Marcus did not anticipate — his colleague Amina, whom he has had feelings for for three years, is there with a new partner.

By 7:45 PM, Marcus has been managing not just the social drinking pressure, but an emotional state the tree has no branch for: grief, jealousy, the particular loneliness of watching someone you care about with someone else.

This is not in the tree. The tree assumed the party would be a standard social-pressure scenario. It did not account for an emotional ambush.

---

## 7:45 PM: The tree fails

His sparkling water is in his hand. He has handled two offers already (Tier 1, successfully). His pull is currently a 2 out of 10.

Then he watches Amina laugh at something her partner says — a particular way she laughs that he recognises — and within thirty seconds the pull goes from 2 to 7.

He reaches for his tree mentally. There is no branch for this. The branches he has are for social offers. This is not a social offer. This is grief wearing the face of a craving.

He stands at the edge of the room with his sparkling water. For about two minutes he is genuinely unsure what he will do next.

---

## 7:47 PM: The improvised branch

He does not have a branch. So he builds one, right now, under pressure.

The raw material he has is the tools from his lessons — not a specific branch, but a set of principles. He applies them sequentially:

**1. Name it, do not act on it.**
He says to himself, silently: *"This is grief. This is not thirst. The alcohol will not fix this. It will numb it for ninety minutes and then make it worse."*

He has said this before in calm moments. Saying it now, when it is hard to believe, is different. He says it twice.

**2. Change your physical position.**
He moves. Away from the line of sight to Amina's table. He walks to where a group of people from another department are talking. He does not join their conversation — he just stands near noise and movement instead of standing in stillness with the feeling.

**3. Text support person.**
He takes out his phone: *"Party. Different trigger. Not social pressure — emotional. Pull is 7. Staying for now. Update you in 15."*

His support person replies: *"7 is high. What is your exit time?"*

He replies: *"8:30."* He has moved the exit time forward from 9:30 to 8:30. He commits to it in writing.

**4. Add it to the tree in real time.**
He opens Notes on his phone and types:

*"Add branch: if person I have feelings for is present with a partner — emotional ambush. Exit time moves immediately to 90 min from arrival. Do not stay past that."*

He writes this at 7:49 PM. He is still at the party. The pull is still at 6.

---

## 8:05 PM — Peer check

He approaches a colleague he trusts — not to tell the full story, but to anchor himself in a conversation. He asks about the colleague's holiday plans. He listens. He responds. He is present in the conversation for twelve minutes.

By 8:17 PM the pull is back to 4.

---

## 8:30 PM — Exit

He leaves at 8:30, exactly as he committed in the text. He has been there two hours. He says brief goodbyes, avoids Amina's table.

In his car, he calls his support person. They talk for twenty minutes.

---

## The debrief

The next morning Marcus updates the tree properly. He adds an entirely new trigger category:

```
EMOTIONAL AMBUSH TRIGGER (e.g. romantic grief, rejection, seeing someone important
with someone else):

  This is NOT a social-pressure trigger. The branches for polite refusals do not apply.

  Step 1: Name it aloud to yourself. "This is [grief/rejection/loneliness]. It is not thirst."
  Step 2: Move your physical position immediately.
  Step 3: Text support person and commit to a new exit time in writing.
  Step 4: Anchor yourself in an active conversation with a neutral person (not about the trigger).
  Step 5: Leave at or before the committed exit time. No exceptions.

  Lesson learned: Check guest list in advance if possible. If the person who caused the
  emotional ambush will be there — shorten exit time to 60–90 minutes from arrival, planned
  before you go in.
```

---

## What this scenario teaches

**A tree is not a guarantee — it is a first draft.**

Every tree has gaps. The gaps are not failures of planning — they are unknowns that only reveal themselves in the event. The correct response to a branch failing is not to abandon the tree. It is to build the missing branch, right now, with whatever tools you have.

Marcus had no branch for emotional ambush. He improvised one from principles. He survived. And the next morning he built the branch properly, so it will exist next time.

**The tree that has been used is more valuable than the tree that has been built.**

A tree built in therapy has the experiences your therapist knows about. A tree that has survived an actual event has the experiences reality has thrown at you. The second tree is more trustworthy.

Do not be discouraged when a branch fails. A failed branch is information. Update the tree and try again.
EOT;
$l['content_sw'] = <<<'EOT'
## Muktadha

Marcus ana miaka 41. Amekuwa katika kupona kutoka kwa pombe kwa miezi kumi na moja. Amefanikiwa kutumia mti wake wa maamuzi katika matukio manne mwaka huu. Ana mti. Amewahi kutumia miti. Hana wasiwasi.

Hii itakuwa hali inayomfundisha somo muhimu zaidi kuhusu miti ya maamuzi.

## Kile Mti Ulikosa

Sherehe iko hoteli. Bar wazi. Muziki wa moja kwa moja. Na — kipengele Marcus hakitarajia — mwenzake Amina, ambaye amekuwa na hisia kwa miaka mitatu, yuko na mshirika mpya.

Hii haimo kwenye mti. Mti ulidhani sherehe ingekuwa hali ya kawaida ya shinikizo la kijamii. Haukuzingatia shambulio la kihisia.

## Kile Hali Hii Inafundisha

Mti si dhamana — ni rasimu ya kwanza.

Kila mti una pengo. Mapengo si kushindwa kwa kupanga — ni mambo yasiyojulikana ambayo yanajifunua tu katika tukio. Jibu sahihi kwa tawi kushindwa si kuacha mti. Ni kujenga tawi linalokosekana, sasa hivi, na zana unazokuwa nazo.

Marcus hakuwa na tawi la shambulio la kihisia. Alifanya ubuni wa moja kutoka kwa kanuni. Aliokoka. Na asubuhi iliyofuata alijenga tawi vizuri.
EOT;
$l['key_takeaways'] = json_encode([
    "A decision tree is a first draft, not a guarantee. Every tree has gaps that only reveal themselves in the actual event.",
    "When a branch fails, do not abandon the tree — improvise from principles: name it, move, text support, anchor in conversation, leave on time.",
    "Emotional ambush triggers (grief, rejection, seeing someone important with someone else) need their own branch — they are NOT social pressure triggers.",
    "Commit your revised exit time in writing, in a text to your support person. A commitment in writing is more binding than one in your head.",
    "A tree that has survived a real event is more trustworthy than a tree built only in therapy. Failed branches are information — update and try again."
]);
$l['key_takeaways_sw'] = json_encode([
    "Mti wa maamuzi ni rasimu ya kwanza, si dhamana. Kila mti una mapengo yanayofunuliwa tu katika tukio halisi.",
    "Tawi likishindwa, usiacha mti — fanya ubuni kutoka kwa kanuni: litaje, sogea, tuma ujumbe, jishikamishe na mazungumzo, ondoka kwa wakati.",
    "Visababishi vya shambulio la kihisia vinahitaji tawi lake mwenyewe — si visababishi vya shinikizo la kijamii.",
    "Thibitisha wakati wako wa kuondoka uliorekebishwa kwa maandishi, kwenye ujumbe kwa mtu wako wa msaada.",
    "Mti uliookoa tukio la kweli unaaminiwa zaidi. Matawi yaliyoshindwa ni habari — sasisha na ujaribu tena."
]);
$lessons[] = $l;

// ── Scenario 4: Building the Tree with Your Therapist ────────────────────
$l = [];
$l['slug']     = 'scenario-branching-with-therapist';
$l['title']    = 'Branching in Practice: Building the Tree Together';
$l['title_sw'] = 'Matawi Katika Vitendo: Kujenga Mti Pamoja';
$l['category'] = 'alcohol';
$l['language'] = 'en';
$l['level']    = 'beginner';
$l['duration_minutes'] = 8;
$l['thumbnail_emoji']  = '🗣️';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 6;
$l['summary']    = 'A session between Rose and her therapist — the actual conversation of building a decision tree for the first time, including the questions that reveal the branches you would not have thought of yourself.';
$l['summary_sw'] = 'Kikao kati ya Rose na mtaalamu wake — mazungumzo halisi ya kujenga mti wa maamuzi kwa mara ya kwanza, ikiwemo maswali yanayofunua matawi ambayo husingekuwa umeyafikiria mwenyewe.';
$l['content'] = <<<'EOT'
## Context

Rose is 26. She has been in alcohol recovery for six weeks — her first extended sober period in four years. She has a session with her therapist today. The agenda: build her first decision tree for her highest-risk scenario.

Her highest-risk scenario, as she has identified it: her friends' group outing, which happens most Friday evenings.

This is a transcript of the session, edited for length.

---

## The session

**Therapist:** Okay, let us start with the Friday group. When you imagine being there this Friday, what is the first moment that worries you?

**Rose:** When I first arrive. Everyone is already drinking. There is always a drink waiting.

**Therapist:** Good. So that is our first branch. What do you think you will do at that moment?

**Rose:** I will say no.

**Therapist:** Say the exact words you will use.

**Rose:** *(pause)* "I am okay, thanks."

**Therapist:** Good. Now — what do you think will happen next?

**Rose:** Someone will push back. Probably Caro. She always does.

**Therapist:** What will Caro say?

**Rose:** She will say something like "Just one, it is Friday."

**Therapist:** And what will you say then?

**Rose:** *(longer pause)* I do not know. That is usually where I get stuck.

**Therapist:** Okay. That is exactly why we are building this. When you do not know, we build the branch now, when you are calm, so you know on Friday. What do you want to say to Caro in that moment?

**Rose:** I want her to stop. I do not want to explain.

**Therapist:** So what phrase would achieve that?

**Rose:** Maybe... "I am taking a break. Let us enjoy the evening."

**Therapist:** Good. Write that down. That is your Caro branch. Now — what if she pushes again?

**Rose:** I repeat it.

**Therapist:** Exactly. Same phrase, same tone. Do not add new information. What does that look like? Say it out loud.

**Rose:** "I am taking a break. Let us enjoy the evening."

**Therapist:** Good. Now I want to ask you something that might feel strange: is there a moment during the evening where you have historically been most likely to give in? Not the first offer. Often it is later.

**Rose:** *(quietly)* When everyone is a few drinks in and I am the only one sober. It gets... separate-feeling.

**Therapist:** Can you describe that feeling more?

**Rose:** Like I am behind glass. They are in one world and I am watching from outside it.

**Therapist:** That is an important branch. The behind-glass feeling. What have you done with that feeling before when you have successfully managed it?

**Rose:** Once I called my sister from the bathroom.

**Therapist:** That is a branch. "When the behind-glass feeling arrives: go to the bathroom and call your sister." What if your sister does not answer?

**Rose:** I could text her.

**Therapist:** Write it: "Call sister. If no answer, text. Stay in the bathroom for five minutes regardless."

**Rose:** *(writes)*

**Therapist:** One more. What is your exit? At what point are you leaving on Friday?

**Rose:** I was thinking maybe ten.

**Therapist:** Is ten realistic? Or is that what you hope will feel natural?

**Rose:** *(laughs slightly)* Ten is optimistic. I usually do not want to be the first to leave.

**Therapist:** Okay. What time do you want to be home by?

**Rose:** Eleven, realistically.

**Therapist:** Then the exit is ten-thirty. Not eleven. Ten-thirty gives you travel time and keeps you from the last, hardest hour when everyone is drunk and generous. Write ten-thirty.

**Rose:** *(writes)* Ten-thirty. I have never left that early before.

**Therapist:** You have never had a tree before.

---

## Rose's tree after the session

```
FRIDAY GROUP OUTING — Decision Tree

ARRIVAL:
  Drink already waiting ──> "Niko sawa, asante." Do not take it.
                             Order your own drink at the bar.
  No drink waiting ──> Order soda water with lime before sitting.

FIRST OFFER (probably Caro):
  "I am taking a break. Let us enjoy the evening."

  If pushed again ──> Exact same phrase. No new information.
  If pushed a third time ──> "Caro I have said it. What were you saying about your job?"
                              (redirect to something she wants to talk about)

BEHIND-GLASS FEELING:
  When it arrives ──> Go to bathroom.
                       Call sister. If no answer, text.
                       Stay in bathroom 5 minutes minimum.
                       Return to the group when feeling is below 5/10.
  If feeling stays above 5/10 after returning ──> Activate exit.

EXIT:
  Leave at 10:30 PM regardless of how the evening feels.
  Text therapist when I am in the car: "Out."
  Do not apologise or over-explain departure.
```

---

## What made the session work

**The therapist asked for the exact words, not the general plan.** "I will say no" is not a branch. "I am okay, thanks" is a branch. The specificity is the point.

**The therapist asked about the middle of the evening, not just the arrival.** Most people plan for the first five minutes. The behind-glass feeling arrives at the forty-minute mark, not the five-minute mark. The tree needs to cover the whole event.

**The therapist challenged the exit time.** Rose had said ten — the therapist pushed to ten-thirty. Negotiating the exit time before the event, when you are clear-headed, produces a more realistic commitment than what you set in the moment.

**The tree is written down.** Not in Rose's head — on paper that she can carry on Friday. The act of writing makes the branches retrievable under pressure.

---

## Building your own tree with your therapist

If you have a session coming up, bring this structure:

1. Name your highest-risk upcoming scenario.
2. Map the first challenge (usually arrival or the first offer).
3. Ask yourself: when in the event am I most likely to give in? Build a branch there.
4. Name the feeling, not just the behaviour — the behind-glass feeling needs its own branch, not just "if I am offered a drink."
5. Set your exit time before you go in. Have your therapist hold you to a realistic time, not an optimistic one.

A tree built collaboratively in a therapy session is better than one built alone — because your therapist will ask the question you have been avoiding.
EOT;
$l['content_sw'] = <<<'EOT'
## Muktadha

Rose ana miaka 26. Amekuwa katika kupona kutoka kwa pombe kwa wiki sita. Ana kikao na mtaalamu wake leo. Ajenda: jenga mti wake wa kwanza wa maamuzi kwa hali yake ya hatari kubwa zaidi — matamshi ya kundi la marafiki wake, ambayo hufanyika Ijumaa nyingi za jioni.

## Kile Kikao Kilifanya Vizuri

Mtaalamu aliomba maneno halisi, si mpango wa jumla. Mtaalamu aliuliza kuhusu katikati ya jioni, si tu kufika. Mtaalamu alipinga wakati wa kuondoka.

Mti uliandikwa. Si kichwani mwa Rose — kwenye karatasi anayoweza kubeba Ijumaa.

## Kujenga Mti Wako na Mtaalamu Wako

Ikiwa una kikao kinakokuja, lete muundo huu:

1. Taja hali yako ya hatari kubwa zaidi inayokuja.
2. Weka ramani ya changamoto ya kwanza.
3. Jiulize: wakati gani katika tukio ninaelekea zaidi kutoa? Jenga tawi huko.
4. Taja hisia, si tabia tu.
5. Weka wakati wako wa kuondoka kabla ya kwenda.

Mti uliojengwa kwa ushirikiano katika kikao cha matibabu ni bora kuliko ule uliojengwa peke yako.
EOT;
$l['key_takeaways'] = json_encode([
    "Build your branches with exact words, not general intentions. 'I will say no' is not a branch. 'I am taking a break, let us enjoy the evening' is a branch.",
    "Map the whole event, not just the first five minutes. The hardest moment is usually 40-60 minutes in, not at arrival.",
    "Name the feeling, not just the behaviour. The behind-glass feeling needs its own branch — it is a separate trigger from a social offer.",
    "Set your exit time before you go in. Negotiate it when calm — your optimistic estimate and your realistic estimate are different times.",
    "A tree built in a therapy session is more complete because your therapist will ask the question you have been avoiding."
]);
$l['key_takeaways_sw'] = json_encode([
    "Jenga matawi yako kwa maneno halisi, si nia za jumla. 'Nitasema hapana' si tawi. 'Ninachukua mapumziko' ni tawi.",
    "Weka ramani ya tukio lote, si dakika tano za kwanza tu.",
    "Taja hisia, si tabia tu. Hisia ya nyuma ya kioo inahitaji tawi lake mwenyewe.",
    "Weka wakati wako wa kuondoka kabla ya kwenda. Yaafiki ukiwa na utulivu.",
    "Mti uliojengwa katika kikao cha matibabu ni kamili zaidi kwa sababu mtaalamu wako atauliza swali unalokimbia."
]);
$lessons[] = $l;

// ── Scenario 5: After a Relapse ───────────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-branching-after-relapse';
$l['title']    = 'Branching After a Relapse: Starting Again';
$l['title_sw'] = 'Matawi Baada ya Kurudi Nyuma: Kuanza Tena';
$l['category'] = 'alcohol';
$l['language'] = 'en';
$l['level']    = 'advanced';
$l['duration_minutes'] = 10;
$l['thumbnail_emoji']  = '🌱';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 7;
$l['summary']    = 'Kevin relapsed on day 94. This is the session where he and his therapist rebuild the decision tree — using the relapse itself as the most accurate data they have ever had.';
$l['summary_sw'] = 'Kevin alirudi nyuma siku ya 94. Hii ni kikao ambapo yeye na mtaalamu wake wanajenga upya mti wa maamuzi — wakitumia kurudi nyuma mwenyewe kama data sahihi zaidi waliyowahi kuwa nayo.';
$l['content'] = <<<'EOT'
## What happened

Kevin is 38. He reached day 94 of sobriety — the longest stretch of his adult life. Then his father was admitted to hospital with a serious illness. Kevin drove four hours to Kisumu, sat at the hospital for two days, and on the night his father was discharged but still critical, he drank.

One night. He stopped the next morning. He told his therapist within 48 hours.

He is now on day 6 of his second attempt. He is ashamed. He is also, if he is honest, frightened — because day 94 was supposed to mean something, and it did not protect him.

---

## The session: What went wrong

**Therapist:** I want to understand what happened, not to judge it, but to find the branches that were missing. You had a tree — we built it in June. What happened to the tree on that night?

**Kevin:** I was not in a social event. The tree is for social events. I was alone in a hotel room in Kisumu and my father might have been dying.

**Therapist:** So the tree did not have a branch for that.

**Kevin:** No. It had nothing for that.

**Therapist:** Okay. Let us map what actually happened. Walk me through the evening.

**Kevin:** My father was discharged at 7 PM. The doctor said he was stable but it was still serious. My brother drove him home. I stayed in the hotel because I had a meeting in Kisumu the next morning. I ate something. I sat in the room. I called my wife. The call was ... it was not a good call. She was relieved about my father but we got into something else. I hung up feeling worse. And then — I just walked down to the bar.

**Therapist:** What was the feeling in the room before you walked down?

**Kevin:** Like... contained terror. Everything was fine, officially. My father was home. But nothing felt safe. I could not settle.

**Therapist:** "Contained terror" is important. What would you have needed in that moment?

**Kevin:** I do not know. To not be alone.

**Therapist:** And was there anyone you could have called?

**Kevin:** *(long pause)* I did not think of it. I thought of the bar.

**Therapist:** That is the branch. Not the bar — the other option that existed but did not occur to you. Who could you have called?

**Kevin:** My brother. He was with my father, but — he would have answered.

**Therapist:** Anyone else?

**Kevin:** My sponsor. But it was late.

**Therapist:** Your sponsor exists for late nights when you are alone in hotel rooms four hours from home feeling contained terror. That is exactly what he is for.

**Kevin:** *(quietly)* I know.

**Therapist:** So the missing branch is: when I am alone in a high-stakes emotional situation and I feel the contained terror, I call someone before I do anything else. Not after I decide I cannot handle it — at the first sign of the feeling.

---

## Rebuilding the tree

The existing tree was built for social events. It stays. They add a new category:

```
CRISIS EMOTIONAL STATE TRIGGER
(Serious family event / medical emergency / bad news about someone I love)

RECOGNITION SIGNS:
  - I am alone
  - Something serious has just happened or is still uncertain
  - I feel "contained terror" — surface okay, inside unsafe
  - A phone call has ended badly or left things unresolved

IMMEDIATE ACTION (within 10 minutes of recognising the state):
  Call someone. In order of preference:
  1. Sponsor (available for this — this is what sponsors are for)
  2. Brother
  3. Wife (only if the earlier call was not the trigger — if it was, skip to 1 or 2)

DO NOT:
  - Stay in the hotel room alone beyond 30 minutes without a call
  - Go anywhere that has alcohol "just to sit"
  - Wait until the feeling is unbearable before calling — call at the first sign

IF ALONE IN A HOTEL (high-risk environment):
  If it is past 8 PM and I am alone and the trigger has fired:
  ──> Contact support person first
  ──> If no answer within 10 minutes: leave the hotel. Go to a 24-hour café,
       a petrol station, anywhere with people and no bar.
  ──> Do not go back to the room until the call has happened.
```

---

## What to do with the shame

Kevin says, near the end of the session:

*"Ninajisikia aibu. Miezi mitatu. Kisha mtu mmoja."*
(I feel ashamed. Three months. Then one person.)

His therapist:

*"Day 94 is still real. You did 94 days of work. The relapse does not delete that work — it adds one more data point. The data point is: crisis emotional state, alone, hotel, late night, bad phone call. That is information you did not have before. Now you have it."*

*"The person who does 94 days and then uses is not back at zero. They are someone who did 94 days, has the skills those days built, and now also knows something about their tree that they did not know before."*

---

## Day 6: The new tree in action

Six days after the relapse, Kevin is in Mombasa for another work trip. Different city. Same category of risk.

He is in his hotel room at 8:30 PM. He is not in crisis. His father is recovering. The trip is routine.

He checks the tree anyway.

He texts his brother: *"Niko Mombasa kwa kazi. Nipigie simu kesho asubuhi?"*
(I am in Mombasa for work. Call me tomorrow morning?)

His brother calls back in ten minutes. They talk for twenty. Kevin goes to sleep at 10 PM.

The call was not necessary. The tree said to call if the trigger fired. The trigger did not fire.

But Kevin called anyway. Because he has learned — at a cost of 94 days — that the branch works better when it is used before it is needed.

---

## What this scenario teaches

**A relapse is the most accurate data you will ever have about your own tree.**

Before the relapse, Kevin knew his triggers in theory. After the relapse, he knows them from experience. The branch he built after day 94 is more precise, more personalised, and more likely to work than anything he could have built in a calm therapy session at day 10.

**The purpose of a relapse debrief is not punishment — it is precision.**

The question is not "why did I fail?" The question is: "What was happening in the three hours before I walked to the bar, and what branch, if it had existed, would have redirected me?"

Every relapse has an answer to that question. Find the answer. Build the branch.

**Recovery is not a straight line. It is a tree that you keep updating.**

The tree after day 6, post-relapse, is better than the tree after day 94, pre-relapse. Not because Kevin is in a better place — he is not, yet — but because the tree is now more accurate.

That is what matters most.
EOT;
$l['content_sw'] = <<<'EOT'
## Kilichotokea

Kevin ana miaka 38. Alifikia siku 94 ya kujiepusha — muda mrefu zaidi wa maisha yake ya utu uzima. Kisha baba yake aliingizwa hospitalini na ugonjwa mbaya. Kevin alikomaa siku ya 94 yake.

Sasa yuko siku ya 6 ya jaribio lake la pili. Ana aibu. Pia, akiwa mkweli, ana hofu.

## Kusudi la Tathmini ya Kurudi Nyuma

Swali si "kwa nini nilishindwa?" Swali ni: "Kilikuwa nini kinaendelea katika masaa matatu kabla sijaenda baarini, na tawi gani, kama lingewepo, lingelielekeza tena?"

Kila kurudi nyuma kuna jibu kwa swali hilo. Tafuta jibu. Jenga tawi.

## Kile Hali Hii Inafundisha

Kurudi nyuma ni data sahihi zaidi utakayowahi kuwa nayo kuhusu mti wako mwenyewe.

Kupona si mstari mnyoofu. Ni mti unaoendelea kusasisha.

Mti baada ya siku 6, baada ya kurudi nyuma, ni bora kuliko mti baada ya siku 94 kabla ya kurudi nyuma — kwa sababu mti sasa ni sahihi zaidi.

Hiyo ndiyo inayomaanisha zaidi.
EOT;
$l['key_takeaways'] = json_encode([
    "A relapse is the most accurate data you will ever have about your own tree. Use it to build the branch that was missing.",
    "The relapse debrief question is not 'why did I fail?' — it is 'what branch, if it had existed, would have redirected me?'",
    "Build a separate branch for crisis emotional states (serious family events, medical emergencies, bad news) — social-event trees do not cover these.",
    "Call your support person at the first sign of the contained-terror feeling — not after you decide you cannot handle it.",
    "Recovery is not a straight line. The tree after a relapse is more accurate than the tree before it. That accuracy is what matters."
]);
$l['key_takeaways_sw'] = json_encode([
    "Kurudi nyuma ni data sahihi zaidi utakayowahi kuwa nayo. Itumie kujenga tawi lililokosekana.",
    "Swali la tathmini ya kurudi nyuma si 'kwa nini nilishindwa?' — ni 'tawi gani, kama lingewepo, lingelielekeza tena?'",
    "Jenga tawi tofauti kwa hali za mgogoro wa kihisia — miti ya matukio ya kijamii haifuniki hizi.",
    "Piga simu mtu wako wa msaada dalili ya kwanza ya hisia ya hofu iliyozuiliwa.",
    "Kupona si mstari mnyoofu. Mti baada ya kurudi nyuma ni sahihi zaidi. Hiyo ndiyo inayomaanisha zaidi."
]);
$lessons[] = $l;

// ── Insert ────────────────────────────────────────────────────────────────
$insert = $pdo->prepare("
    INSERT INTO lessons
        (title, title_sw, slug, category, language, level, duration_minutes,
         thumbnail_emoji, is_premium, is_published, `order`,
         summary, summary_sw, content, content_sw, key_takeaways, key_takeaways_sw,
         created_at, updated_at)
    VALUES
        (:title, :title_sw, :slug, :category, :language, :level, :duration_minutes,
         :thumbnail_emoji, :is_premium, :is_published, :order,
         :summary, :summary_sw, :content, :content_sw, :key_takeaways, :key_takeaways_sw,
         NOW(), NOW())
    ON DUPLICATE KEY UPDATE
        title=VALUES(title), title_sw=VALUES(title_sw),
        category=VALUES(category), level=VALUES(level), duration_minutes=VALUES(duration_minutes),
        thumbnail_emoji=VALUES(thumbnail_emoji), is_premium=VALUES(is_premium),
        is_published=VALUES(is_published), `order`=VALUES(`order`),
        summary=VALUES(summary), summary_sw=VALUES(summary_sw),
        content=VALUES(content), content_sw=VALUES(content_sw),
        key_takeaways=VALUES(key_takeaways), key_takeaways_sw=VALUES(key_takeaways_sw),
        updated_at=NOW()
");

$inserted = 0; $errors = [];
foreach ($lessons as $l) {
    try {
        $insert->execute([
            ':title'            => $l['title'],
            ':title_sw'         => $l['title_sw'],
            ':slug'             => $l['slug'],
            ':category'         => $l['category'],
            ':language'         => $l['language'],
            ':level'            => $l['level'],
            ':duration_minutes' => $l['duration_minutes'],
            ':thumbnail_emoji'  => $l['thumbnail_emoji'],
            ':is_premium'       => $l['is_premium'],
            ':is_published'     => $l['is_published'],
            ':order'            => $l['order'],
            ':summary'          => $l['summary'],
            ':summary_sw'       => $l['summary_sw'],
            ':content'          => $l['content'],
            ':content_sw'       => $l['content_sw'],
            ':key_takeaways'    => $l['key_takeaways'],
            ':key_takeaways_sw' => $l['key_takeaways_sw'],
        ]);
        $inserted++;
    } catch (Exception $e) {
        $errors[] = $l['slug'] . ': ' . $e->getMessage();
    }
}

$rows = $pdo->query("SELECT id, slug, title, LENGTH(content) as len FROM lessons WHERE category='alcohol' ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['inserted' => $inserted, 'errors' => $errors, 'alcohol_lessons' => $rows]);
unlink(__FILE__);
