<?php
$pdo = new PDO('mysql:host=localhost;dbname=qnztnquh_uberhealth', 'qnztnquh_uberhdb', 'Uber@Health2026!');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$lessons = [];

// ── Scenario 1: The Supermarket ───────────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-alcohol-supermarket';
$l['title']    = 'Scenario: The Supermarket Aisle';
$l['title_sw'] = 'Hali Halisi: Korridor ya Duka Kubwa';
$l['category'] = 'alcohol';
$l['language'] = 'en';
$l['level']    = 'beginner';
$l['duration_minutes'] = 7;
$l['thumbnail_emoji']  = '🛒';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 8;
$l['summary']    = 'Wanjiru is twelve days sober. She is at Naivas doing a routine shop when the craving arrives without warning — and she has to urge-surf in the middle of the drinks aisle.';
$l['summary_sw'] = 'Wanjiru ana siku kumi na mbili bila pombe. Yuko Naivas akifanya ununuzi wa kawaida wakati hamu inakuja bila onyo — na lazima asafirie hamu katikati ya korridor ya vinywaji.';
$l['content'] = <<<'EOT'
## Background

Wanjiru is 30. She is twelve days into her first serious attempt at alcohol recovery. Things have been manageable — she has been staying home in the evenings, avoiding the bar routes, keeping busy.

Today is a Tuesday afternoon. She is at Naivas doing a routine grocery shop. She has been to this supermarket a hundred times. She did not think of it as a high-risk environment.

She was wrong.

---

## The moment (3:22 PM)

She turns into aisle seven for cooking oil. Aisle seven at this Naivas also has the alcohol section.

She sees it before she registers seeing it: a wall of Johnnie Walker, Konyagi, White Cap, Chrome Vodka. Arranged perfectly. Chilled. The familiar gold and black labels.

Her feet stop. She does not choose to stop. They just stop.

**What happens inside her body:**
Her mouth produces a tiny amount of saliva. Her chest tightens slightly. Her eyes track to the Konyagi — not Johnnie Walker, which she drank occasionally, but Konyagi, which she drank most nights for three years.

The craving is physical before it is mental. It is in her throat, in her hands, in the memory of how a poured glass looks at 7 PM.

Then the mental layer arrives: *"One small one. I have been so good for twelve days. I can reset tomorrow."*

---

## What urge-surfing looks like from inside

She is standing in front of the Konyagi. She has been standing there for approximately forty seconds.

She does not move. Not toward it, not away. She does what she was taught to do.

**1. She names it out loud — quietly, under her breath:**
*"Hii ni hamu. Inakuja. Itapita."*
(This is a craving. It is coming. It will pass.)

A woman nearby glances at her. Wanjiru does not care.

**2. She observes without acting:**
She notices: mouth, chest, hands, eyes wanting to go to the label. She is curious about this, the way you might be curious about something happening in another room. The craving is real and present. It is also — and this is the thing — not actually her. It is a wave. She is the shore.

**3. She checks the physical sensation at its peak:**
The tightness in her chest is a 7 out of 10. She breathes into it. Four counts in. The tightness does not disappear but it shifts. It is no longer a wall — it is a wave. She can feel it cresting.

**4. She waits:**
She stands there. Sixty seconds. Ninety seconds. Two minutes. She is still in front of the Konyagi.

At two minutes and fifteen seconds, the tightness begins to ease. Not gone — reduced. It is a 4 now, not a 7.

She turns and walks to the end of the aisle. She does not run. She walks normally.

---

## At the checkout (3:31 PM)

She is in the queue. Her basket has: cooking oil, bread, spinach, milk, two mangoes.

She texts her sponsor: *"Was in Naivas. Alcohol aisle. Stood for two minutes. Did not take anything. Hands are still a bit shaky."*

Her sponsor replies: *"That is a win. Twelve days and you just surfed your first unplanned craving in a public place. How do you feel?"*

Wanjiru: *"Strange. Like I did not know I had that in me."*

Sponsor: *"You do. That is what you just found out."*

---

## The debrief: What she learns

That evening she writes in her journal:

**What triggered it:** Visual cue — specific label (Konyagi). Not social pressure. Not stress. Pure associative memory: that label = that ritual = that feeling.

**What the craving felt like at peak:** 7/10. Physical first (mouth, chest, hands), mental second. The mental layer arrived about 15 seconds after the physical.

**What worked:** Naming it. Staying still instead of fleeing. Observing it like a wave. Waiting for the peak to pass. It peaked at roughly ninety seconds and reduced significantly by two minutes.

**What she will do differently:** At Naivas, take the other route to cooking oil — through the cereals, not through aisle seven. This is not avoidance as a permanent strategy. It is environmental protection during weeks one through eight, when the neural pathway is still active and visual cues are at their strongest.

---

## What this scenario teaches about urge surfing

**The craving always peaks and passes.** Wanjiru did not know this in advance — she had been told it, but she had not experienced it. Standing in front of the Konyagi for two minutes was not comfortable. It was the opposite. But by the end of those two minutes she had proof, in her own body, that the wave crests and recedes.

That proof is worth more than anything her sponsor or therapist could have told her. She knows it now from the inside.

**You do not have to leave.** Fleeing a craving feels like the safer option. But fleeing also trains the brain that the craving is dangerous — something to escape rather than something to observe and wait out. Urge surfing means staying present with the sensation long enough to watch it peak. This is uncomfortable. It is also what makes the next craving slightly less powerful.

**Visual cues are the strongest triggers in early recovery.** Specific brands, specific packaging, specific smells. The brain has spent years pairing these images with the reward of the substance. The pairing does not break on day one of sobriety — it fades over months of non-reinforcement. In the meantime, route control (choosing paths that avoid strong visual cues) is a legitimate recovery tool.
EOT;
$l['content_sw'] = <<<'EOT'
## Muktadha

Wanjiru ana miaka 30. Ana siku kumi na mbili katika jaribio lake la kwanza la kurejesha upya kutoka kwa pombe. Leo ni Jumanne mchana. Yuko Naivas akifanya ununuzi wa kawaida. Amekwenda duka hili mara mia moja. Hakufikiria kuwa ni mazingira ya hatari kubwa.

Alikosea.

## Kile Urge-Surfing Inaonekana Kama Kutoka Ndani

Wanjiru anasimama mbele ya Konyagi. Amekuwa akisimama huko kwa takriban sekunde arobaini.

Hasogei. Si kuelekea kwake, si mbali. Anafanya alichofunzwa.

1. Anaitaja kwa sauti — kimya, chini ya pumzi yake: "Hii ni hamu. Inakuja. Itapita."

2. Anaangalia bila kutenda: ana udadisi kuhusu hili, kama unavyokuwa na udadisi kuhusu kitu kinachoendelea chumbani kingine.

3. Anasubiri: Sekunde sitini. Tisini. Dakika mbili. Bado yuko mbele ya Konyagi.

Dakika mbili na sekunde kumi na tano, shinikizo linaanza kupungua.

## Kile Hali Hii Inafundisha

Hamu daima hupanda na kupita. Wanjiru hakujua hili mapema — alikuwa ameambiwa, lakini hakuwahi kuipitia. Kusimama mbele ya Konyagi kwa dakika mbili haikuwa ya starehe. Lakini mwishoni mwa dakika hizo mbili alikuwa na uthibitisho, katika mwili wake mwenyewe, kwamba wimbi hupanda na kupungua.
EOT;
$l['key_takeaways'] = json_encode([
    "Cravings peak and pass. The wave crests at roughly 90 seconds — if you stay present without acting, you will feel it reduce.",
    "Physical sensations arrive before the mental narrative. Notice the body first: mouth, chest, hands — then watch the mental layer arrive.",
    "Name it aloud: 'This is a craving. It is coming. It will pass.' Speaking it creates distance between you and the sensation.",
    "Fleeing a craving trains the brain that it is dangerous. Surfing it — staying present until the peak passes — makes the next one slightly weaker.",
    "In early recovery, route control is legitimate: choose supermarket aisles, streets, and venues that reduce strong visual cues until the neural pathway fades."
]);
$l['key_takeaways_sw'] = json_encode([
    "Hamu hupanda na kupita. Wimbi hupanda takriban sekunde tisini — ukibaki bila kutenda, utaihisi ikipungua.",
    "Hisia za kimwili zinakuja kabla ya hadithi ya akili. Tazama mwili kwanza: mdomo, kifua, mikono.",
    "Itaje kwa sauti: 'Hii ni hamu. Inakuja. Itapita.' Kuzungumza kunaunda umbali kati yako na hisia.",
    "Kukimbia hamu kunafundisha ubongo kwamba ni hatari. Kuisafiri kunafanya inayofuata kuwa dhaifu kidogo.",
    "Katika kupona mapema, udhibiti wa njia ni halali: chagua korridoa, mitaa, na maeneo yanayopunguza ishara kali za kuona."
]);
$lessons[] = $l;

// ── Scenario 2: The Long Evening ─────────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-alcohol-long-evening';
$l['title']    = 'Scenario: The Long Evening at Home';
$l['title_sw'] = 'Hali Halisi: Jioni Ndefu Nyumbani';
$l['category'] = 'alcohol';
$l['language'] = 'en';
$l['level']    = 'beginner';
$l['duration_minutes'] = 8;
$l['thumbnail_emoji']  = '🌆';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 9;
$l['summary']    = 'The 7 PM to 10 PM window is the most dangerous time in early alcohol recovery. Follow Joseph through his first sober Friday evening — the boredom, the restlessness, and what he does with it.';
$l['summary_sw'] = 'Dirisha la saa moja usiku hadi saa nne usiku ni wakati hatari zaidi katika kupona mapema kutoka kwa pombe. Fuata Joseph katika Ijumaa yake ya kwanza ya usiku bila pombe.';
$l['content'] = <<<'EOT'
## Background

Joseph is 44. He is twenty-three days into alcohol recovery. A construction supervisor in Thika. He drank every evening for eleven years — starting at around 7 PM, finishing when the money ran out or he fell asleep.

The evenings are where recovery is won or lost for Joseph. The days are manageable: work, noise, movement, other people. The evenings are empty in a way they never used to be.

This is his first sober Friday evening.

---

## 6:45 PM: Coming home

He gets home. He washes, changes into home clothes. He sits.

The house is quiet in a way that used to be solved immediately — beer from the fridge, the first cold swallow, the shoulders dropping. That was the ritual. Now the ritual is gone and the quietness has nothing to absorb it.

He is not in crisis. He is in the thing that comes before crisis: a restless, itchy, purposeless feeling that he does not have a good word for in English. In Kikuyu he would call it *gûtigwo* — roughly, the feeling of something missing.

He has been told this feeling is temporary. He does not fully believe it.

---

## 7:00 PM: The first wave

He stands up. He sits back down. He picks up his phone. He puts it down.

This is urge behaviour — not a craving exactly, but the restlessness that precedes craving when there is no stimulus. His brain is scanning for the dopamine hit it has received at this time every evening for eleven years. It is not finding it. It is getting louder.

He does what his therapist suggested for this exact time: he opens the journal app on his phone and writes one sentence.

*"7 PM. Restless. Not craving exactly but the house is too quiet."*

The act of writing it down does something small but real: it converts the feeling from an atmosphere he is inside to an object he is looking at. He is still in the house. But he is slightly less inside the feeling.

---

## 7:30 PM: The replacement ritual

He has been working on what his therapist called a replacement ritual — something that occupies the 7-to-9 window, uses his hands, and does not require other people.

He decided on cooking. Not warming something up — actually cooking. Tonight it is beef stew.

He chops onions. He browns the meat. The smells are good. His hands are busy. The stove is on, the kitchen is warm, and the activity has a beginning, a middle, and an end. This is important: the restlessness is partly the brain looking for a structured activity. Cooking provides it.

By 8:15 PM he has eaten. The restless feeling is a 3 out of 10. At 7 PM it was a 6.

**What the replacement ritual is doing:** It is not suppressing the craving — it is occupying the neural bandwidth that the craving uses. A busy prefrontal cortex has less capacity for the craving narrative. Hands that are doing something have less capacity for the automatic reach.

---

## 8:30 PM: The 8:30 wall

He hits what he has come to think of as the 8:30 wall. The stew is done, the dishes are washed, and the evening stretches ahead again. Two more hours before he can reasonably go to sleep.

The pull is back. Not as strong as 7 PM, but present.

He has a protocol for this moment too. He calls his brother.

*"Uko?"* (You there?)

*"Niko, iko nini?"* (I am here, what is it?)

*"Hapana kitu, nilikuwa naomba tuongee kidogo."* (Nothing, I just wanted to talk for a bit.)

They talk for forty minutes. About nothing in particular: his brother's children, a football match coming up, a cousin who has moved to Qatar. Joseph does not mention recovery or the wall or the craving. He does not need to. The conversation is the intervention — it fills the bandwidth, grounds him in relationship, and carries him through the dangerous window.

---

## 9:10 PM: The other side

The call ends. Joseph makes tea. He sits with his phone and watches thirty minutes of football highlights. He brushes his teeth at 9:50 PM — earlier than he has gone to bed in years.

He lies in bed and picks up the journal app again.

*"Got through it. 7 PM was the hardest. Cooking helped. Called bro at 8:30. Tomorrow is Saturday — plan the morning before 9 AM or it will come apart."*

He closes the app. He sleeps.

---

## The 7-to-10 PM window: What Joseph's experience shows

The 7 to 10 PM window is the highest-risk period for people who drank in the evenings. This is not coincidence. It is the time the brain has learned to expect the substance. The craving system activates on schedule even when no trigger event is present.

What works in this window:

**1. Name and observe.** The journal sentence at 7 PM was not dramatic. It took twenty seconds. But it converted the feeling from a state he was inside to something he was looking at.

**2. Occupy your hands.** The restlessness lives in the body. Cooking, cleaning, fixing something, building something — any activity that occupies the hands and has a visible outcome reduces the bandwidth available for the craving narrative.

**3. Carry the window, do not sit through it.** The call at 8:30 PM was not because Joseph needed crisis support. He called because the window needed carrying and human contact is one of the most effective ways to carry it. The call does not have to be about recovery.

**4. Plan the next morning before you sleep.** The end-of-day journal note about Saturday morning is not journaling for its own sake. It is closing the loop — the brain that goes to sleep with a plan for tomorrow is less likely to generate middle-of-the-night anxiety that feeds craving.

---

## What changes over time

Joseph is on day 23. The 7-to-10 window will not always feel like this.

By day 60, the peak craving time will have shifted or reduced. By month four, many people report the evening window becoming neutral — even enjoyable. The replacement ritual becomes genuinely pleasurable rather than a distraction.

This is the neurological reality: the brain is reshaping its reward circuitry. It takes time. The early weeks are when the old circuitry is loudest. The task is simply to get through the window, evening by evening, until the circuitry has had enough non-reinforcement to quiet down.

Day 23 is loud. Day 90 is quieter. Day 180 is different territory entirely.
EOT;
$l['content_sw'] = <<<'EOT'
## Muktadha

Joseph ana miaka 44. Ana siku ishirini na tatu katika kupona kutoka kwa pombe. Msimamizi wa ujenzi Thika. Alinywa kila jioni kwa miaka kumi na moja — kuanza takriban saa moja usiku.

Jioni ni mahali ambapo kupona kunashindwa au kushinda kwa Joseph. Siku ni zinazoweza kushughulikiwa: kazi, kelele, harakati, watu wengine. Jioni ni tupu kwa njia ambazo hazikuwa zote nyakati zilizopita.

## Dirisha la Saa Moja hadi Saa Nne Usiku

Dirisha la saa moja hadi saa nne usiku ni kipindi cha hatari kubwa zaidi kwa watu waliokunywaa jioni. Hii si bahati mbaya. Ni wakati ubongo umejifunza kutarajia dawa. Mfumo wa hamu unawaka kwa ratiba hata wakati hakuna tukio la kisababishi.

Kile kinachofanya kazi katika dirisha hili:

1. Itaje na uangalie — sentensi ya jarida dakika mbili ilikuwa si ya kushangaza. Ilichukua sekunde ishirini. Lakini ilibadilisha hisia kutoka hali aliyokuwa ndani yake hadi kitu alichokuwa akitazama.

2. Shughulisha mikono yako — upole unaishi mwilini. Kupika, kusafisha, kutengeneza kitu.

3. Beba dirisha, usikae ndani yake — simu saa 8:30 haikuwa kwa sababu Joseph alihitaji msaada wa dharura. Alipiga simu kwa sababu dirisha lilihitaji kubebwa.
EOT;
$l['key_takeaways'] = json_encode([
    "The 7-to-10 PM window is the highest-risk time for evening drinkers — the brain activates craving on schedule even without a trigger event.",
    "Writing one sentence in a journal converts the feeling from an atmosphere you are inside to an object you are observing. Twenty seconds, significant effect.",
    "Occupy your hands with an activity that has a beginning, middle, and end — cooking is ideal. Busy hands reduce the bandwidth available for the craving narrative.",
    "Human contact carries the window. The call does not have to be about recovery — any genuine conversation works.",
    "Plan the next morning before you sleep. The brain that goes to sleep with a plan generates less overnight anxiety that feeds morning craving."
]);
$l['key_takeaways_sw'] = json_encode([
    "Dirisha la saa moja hadi saa nne usiku ni wakati wa hatari kubwa zaidi kwa watu waliokunywaa jioni.",
    "Kuandika sentensi moja kwenye jarida kubadilisha hisia kutoka hali unayoishi ndani yake hadi kitu unachotazama.",
    "Shughulisha mikono yako na shughuli inayokuwa na mwanzo, katikati, na mwisho. Mikono yenye shughuli inapunguza uwezo unaopatikana kwa hadithi ya hamu.",
    "Mawasiliano ya kibinadamu hubeba dirisha. Simu hailazimike kuhusu kupona.",
    "Panga asubuhi inayofuata kabla hujalala."
]);
$lessons[] = $l;

// ── Scenario 3: The Funeral ───────────────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-alcohol-funeral';
$l['title']    = 'Scenario: The Funeral';
$l['title_sw'] = 'Hali Halisi: Mazishi';
$l['category'] = 'alcohol';
$l['language'] = 'en';
$l['level']    = 'intermediate';
$l['duration_minutes'] = 9;
$l['thumbnail_emoji']  = '🕊️';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 10;
$l['summary']    = 'Samuel is two months sober when his uncle dies. At the vigil in the village, grief and alcohol are inseparable — but he has learned something about the difference between the grief and the craving.';
$l['summary_sw'] = 'Samuel ana miezi miwili bila pombe wakati mjomba wake anafariki. Katika kesha kijijini, huzuni na pombe hazitenganishiki — lakini amejifunza kitu kuhusu tofauti kati ya huzuni na hamu.';
$l['content'] = <<<'EOT'
## Background

Samuel is 36. Two months and eight days sober. His uncle — the man who paid his school fees when his father left — dies on a Tuesday night from a stroke.

The burial is in the village, two hours from Nairobi. Samuel will be there for two days.

This is the hardest scenario he has faced since he stopped. Not because of social pressure. Because grief is its own trigger, and grief in a Kenyan village setting arrives wrapped in chang'aa and mursik and community, and the boundary between mourning and drinking is not a clear line.

He talks to his therapist on Wednesday morning before he drives down.

---

## The Wednesday session: Preparation

**Therapist:** What are you most worried about?

**Samuel:** Not the first day. The night. The vigil. People drink through the night at vigils. It is how we sit with the body. My uncle — he loved his drink. It will be part of how people remember him.

**Therapist:** So the social context is grief-drinking — not celebratory, but grief.

**Samuel:** Yes. And I have always been a grief drinker. Not just social. When things are painful I drink to get through the pain. This is the most painful thing that has happened since I stopped.

**Therapist:** Okay. Two things I want to say. First: grief and the craving for alcohol are two separate things that will feel like one thing tonight. They are not. The grief is real and it is allowed. The craving is a physical response to emotional pain. They will both be present, but they are different. The craving does not have to be answered in order for the grief to be honoured.

**Samuel:** *(quietly)* I have not thought of it that way.

**Therapist:** Second: what would your uncle want? Not as a pressure — as information. You knew him.

**Samuel:** *(long pause)* He stopped drinking himself, the last two years. He was proud of me for stopping. He told me in March.

**Therapist:** Hold that. That is a branch.

---

## The decision tree he builds for the next two days

```
VIGIL AND BURIAL — Decision Tree

GRIEF TRIGGER (sadness, memory flooding, the body in the room):
  Name it: "This is grief. The grief is allowed."
  Do NOT use alcohol to process it. The grief will still be there in the morning.
  If overwhelming: step outside. Sit with it alone for ten minutes.
  Call therapist if needed (she has said she is available).

ALCOHOL OFFERED:
  "Hapana asante" — no explanation at a vigil. Most people will not push.
  If pushed: "Najishughulikia." (I am looking after myself.)
  This phrase works in village settings — it implies a health or personal reason
  without opening a conversation.

THE NIGHT (vigil, 10 PM–4 AM):
  Most vulnerable window.
  If pull exceeds 6/10: leave the main gathering. Go to the car or to where
  the women are cooking. Return after 20 minutes.
  Text support person at midnight regardless of how I feel: check-in.

THE BRANCH FROM THE SESSION:
  When the craving peaks: think of uncle's last two years.
  He was proud of me in March. I will honour that today.
```

---

## Thursday: The vigil

He arrives at 4 PM. The body is in the sitting room, surrounded by family. The smell of the house — familiar from childhood. His aunt is weeping quietly. His cousins are there.

By 6 PM, a bottle of changaa is being passed among the men outside. His cousin hands it to him without words — a gesture, not a verbal offer.

He passes it to the man on his left. No words. The gesture moves on.

By 9 PM he is tired and sad and present. Not craving. The grief is clean in a way he did not expect — because he is in it, not numbing it. He can feel the specific weight of this man's absence, the school fees, the March phone call, the particular laugh.

He would not have been able to feel this clearly drunk. He knows this from experience — he has been to funerals drunk. This is different.

---

## 1:30 AM: The hardest moment

The vigil is deep. People are singing hymns. A group of older men have been drinking steadily for six hours. The atmosphere is thick with grief and alcohol and woodsmoke.

Samuel is sitting next to his aunt. She is asleep on his shoulder.

The pull arrives suddenly, at a 7. Not because of the social atmosphere — because of the silence inside it. His uncle is gone. He is sitting with that fact at 1:30 in the morning with a sleeping old woman on his shoulder and the weight of it is very heavy.

He texts his support contact: *"1:30 AM. Vigil. Pull 7. Not going anywhere. Just naming it."*

Reply comes in six minutes: *"I am here. Stay with your aunt. You are doing the right thing."*

He puts his phone away. He breathes. He looks at his uncle's photograph on the wall — a man in a suit, a young man, before everything.

**The branch from the session arrives automatically:** He was proud of me in March.

He says it to himself twice. Not as a magic formula — as a true thing to hold onto when the pull is at its loudest.

The pull reduces to a 4 by 2 AM.

---

## Friday: The burial

He gets through the burial. The crying is real and uncomplicated. He carries his share of the coffin. He shovels his share of the earth.

In the car on the way back to Nairobi, he calls his therapist.

*"Nimefanya. Mbili saa ngumu — moja moja na thelathini usiku. Lakini nilifanya."*
(I made it. Two hard hours — one at 1:30 AM. But I made it.)

Therapist: *"How was the grief?"*

Samuel: *"Real. More real than I remember grief being. I think I have been numbing it for a long time."*

Therapist: *"That is what recovery gives you. Not just sobriety — access to your actual feelings. Including the ones that are painful."*

---

## What this scenario teaches

**Grief and craving feel like one thing but they are two different things.**

The grief at the vigil was Samuel's. It belonged to him. It was about his uncle, the school fees, the March phone call. It needed to be felt, not numbed.

The craving at 1:30 AM was a physiological response to emotional pain. It was not a message that he needed alcohol — it was a signal that he was in pain. The appropriate response to pain is not anaesthesia. It is presence, connection, and time.

**Alcohol would not have honoured his uncle. Sobriety did.**

His uncle spent his last two years sober. He told Samuel in March that he was proud of him. Honouring that — sitting with the grief without drinking through it — was the more meaningful act.

**Recovery gives access to grief, not protection from it.**

This is harder and better than it sounds. The grief Samuel felt on Friday was fuller and more true than any grief he had felt at a funeral while drinking. He was present for it. He will carry it, and it will become part of how he remembers his uncle.

That is what he was protecting when he put the bottle down in February.
EOT;
$l['content_sw'] = <<<'EOT'
## Muktadha

Samuel ana miaka 36. Ana miezi miwili na siku nane bila pombe. Mjomba wake — mtu aliyolipa ada zake za shule baba yake alipoondoka — anafariki Jumanne usiku kutokana na kiharusi.

Hii ni hali ngumu zaidi aliyokabili tangu alisimama. Si kwa sababu ya shinikizo la kijamii. Kwa sababu huzuni ni kisababishi chake mwenyewe, na huzuni katika mazingira ya kijiji cha Kenya inakuja imefunikwa na chang'aa na maziwa ya mgando na jamii.

## Kile Hali Hii Inafundisha

Huzuni na hamu vinahisi kama kitu kimoja lakini ni vitu viwili tofauti.

Huzuni katika kesha ilikuwa yake Samuel. Ilihitajika kuhisiwa, si kutiwa ganzi.

Hamu saa moja na nusu usiku ilikuwa jibu la kisaikolojia kwa maumivu ya kihisia. Haikuwa ujumbe kwamba alihitaji pombe — ilikuwa ishara kwamba alikuwa na maumivu.

Kupona kunatoa ufikiaji wa huzuni, si ulinzi kutoka kwake.
EOT;
$l['key_takeaways'] = json_encode([
    "Grief and craving feel like one thing but they are two separate things. Name them separately: 'This is grief. This is the craving.'",
    "Alcohol would not have honoured his uncle — sobriety did. Connecting your recovery to something that matters beyond yourself is one of the most reliable anchors.",
    "In grief settings, the phrase 'Najishughulikia' (I am looking after myself) works without explanation and is respected in village contexts.",
    "Recovery gives you access to grief, not protection from it. The grief felt in sobriety is fuller and more true than grief numbed by alcohol.",
    "A support text at the peak moment does not require a response that solves anything — knowing someone received your message is enough to hold the pull."
]);
$l['key_takeaways_sw'] = json_encode([
    "Huzuni na hamu vinahisi kama kitu kimoja lakini ni vitu viwili tofauti. Vitaje tofauti.",
    "Msemo 'Najishughulikia' unafanya kazi bila maelezo na unaheshimiwa katika mazingira ya kijijini.",
    "Kupona kunakupa ufikiaji wa huzuni, si ulinzi kutoka kwake. Huzuni inayohisiwa katika kujiepusha ni kamili na ya kweli zaidi.",
    "Ujumbe wa msaada wakati wa kilele hauhitaji jibu linalotatua chochote — kujua mtu alipokea ujumbe wako ni ya kutosha."
]);
$lessons[] = $l;

// ── Scenario 4: The Fight at Home ─────────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-alcohol-fight-at-home';
$l['title']    = 'Scenario: The Fight at Home';
$l['title_sw'] = 'Hali Halisi: Ugomvi Nyumbani';
$l['category'] = 'alcohol';
$l['language'] = 'en';
$l['level']    = 'intermediate';
$l['duration_minutes'] = 8;
$l['thumbnail_emoji']  = '🏠';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 11;
$l['summary']    = 'An argument with his wife sends Patrick to the door with his keys in his hand. He knows exactly where he would go. This is what he does instead.';
$l['summary_sw'] = 'Ugomvi na mke wake unamtuma Patrick mlangoni na funguo mkononi mwake. Anajua haswa angekwenda wapi. Hii ndiyo anayofanya badala yake.';
$l['content'] = <<<'EOT'
## Background

Patrick is 42. Three months and two weeks sober. He lives in Umoja with his wife and two children.

His link between anger and alcohol is direct and well-established. He did not drink because he was happy, or because of social situations, or because of boredom. He drank when he was angry — specifically, when he felt wrongly accused, dismissed, or disrespected by people he loved. The anger would hit a threshold, and the fastest exit from that unbearable feeling was the bar.

He has been working on this in therapy. He has a plan. Tonight the plan is tested.

---

## 9:15 PM: The argument

It starts over something small — it always starts over something small. Tonight it is about money that was spent without discussion. It escalates in the way arguments in small houses do: quickly, with words that mean more than they should.

His wife says something that hits the specific nerve — the one about not being trusted, not being treated like an adult — and Patrick feels the anger arrive like a temperature change.

He does not shout. He goes quiet, which in his history has always been worse.

He stands up. He picks up his keys from the table. He walks to the front door.

He knows exactly where he is going. The bar is eight minutes from the house. He has walked there on legs this same degree of angry more times than he can count.

---

## At the door (9:22 PM)

His hand is on the door handle.

He has a rule — one his therapist made him rehearse out loud in a session: **before you open the door, name what you are about to do.**

*"I am about to go to the bar because I am angry."*

He says this quietly to himself, his hand still on the handle.

That sentence contains three things his brain had compressed into one impulse: the action (go to the bar), the mechanism (because), and the feeling (angry).

Separating them changes the electrical charge slightly. He is still angry. The keys are still in his hand. But the next move is no longer automatic.

---

## The next four minutes

He does not go back into the house. Going back into the house right now would re-enter the argument. His wife is still in the sitting room.

He goes to the kitchen instead. He fills a glass of water and drinks it standing at the sink.

He runs the STOP sequence. The breath is harder to take than usual — his body is flooded with cortisol, his breathing is already shallow. He takes one deliberate breath anyway. It does not resolve the anger. It creates a small gap.

He texts his support person — a man from his recovery group:

*"Mbaya. Ugomvi na mke. Funguo zilikuwa mkononi. Niko jikoni."*
(Bad one. Fight with wife. Keys were in my hand. I am in the kitchen.)

His support person replies in four minutes: *"Jikoni ni sawa. Kaa huko. Nipigie simu."*
(Kitchen is good. Stay there. Call me.)

He calls.

---

## The call (9:28 PM to 9:51 PM)

The first five minutes of the call he is still angry. He talks about what his wife said. His support person listens and does not fix anything.

Around minute eight, the support person says: *"You called me instead of opening that door. That is recovery. That exact moment — you called instead of going. That is it."*

Patrick does not feel good about this yet. He still feels the anger. But the anger is reducing. Not because the argument is resolved — it is not — but because twenty-three minutes have passed since he picked up his keys, and twenty-three minutes is enough time for the initial cortisol spike to begin metabolising.

He hangs up at 9:51 PM. He is still in the kitchen.

---

## 10:05 PM

He goes back into the sitting room. His wife is watching television. The argument is not resolved but the temperature has dropped.

*"Tuongee kesho."* (Let us talk tomorrow.)

She nods. He goes to bed. He does not sleep easily but he sleeps.

---

## The morning

He and his wife talk at breakfast. The underlying issue is real and needs to be addressed. They agree to address it properly over the weekend.

In his recovery journal that morning:

*"Last night. Ugomvi. Keys in hand. Did not go. Called support. Kitchen to bed. The argument is real and we will work on it. Recovery is not about the argument being solved — it is about me not going to the bar every time an argument is not solved."*

That last sentence is the one he brings to his next therapy session.

---

## What this scenario teaches

**The "name it before you open the door" rule.**

Patrick's therapist gave him one instruction for the anger-to-bar sequence: before you open the door, say aloud what you are about to do. "I am about to go to the bar because I am angry."

This works because the impulse compresses three things — action, mechanism, feeling — into a single automatic gesture. The sentence decompresses them. The decompression does not remove the anger. But it removes the automaticity. And removal of automaticity is the space in which a different choice becomes possible.

**The kitchen as an intermediate space.**

Patrick did not go back to the argument and he did not go to the bar. He went to the kitchen. This is the technique of intermediate space: when the choice is between two unacceptable options, find a third physical location that is neither. The kitchen, the staircase, the car with the engine off, the balcony. Anywhere that is not the argument and not the bar.

**Time is the intervention.**

The cortisol spike that produces the most extreme anger response typically metabolises within twenty to forty minutes if you do not add fuel to it. Patrick's support call lasted twenty-three minutes. By the time it ended, the physiological peak had passed. He was still angry — but the anger was now a 4, not a 9, and a 4 is manageable.

This is why the instruction "stay in the kitchen" works even if you feel nothing is being solved by staying in the kitchen. Something is being solved: time is passing. The peak is metabolising. The door you almost opened is still closed.
EOT;
$l['content_sw'] = <<<'EOT'
## Muktadha

Patrick ana miaka 42. Ana miezi mitatu na wiki mbili bila pombe. Anaishi Umoja na mke wake na watoto wawili.

Uhusiano wake kati ya hasira na pombe ni wa moja kwa moja na uliowekwa vizuri. Hakukunywa kwa sababu alifurahi, au kwa sababu ya hali za kijamii. Alikunywa alipokuwa na hasira — hasa alipohisi kuhukumiwa vibaya au kudharauliwa na watu aliowapenda.

## Sheria ya "Itaje Kabla ya Kufungua Mlango"

Mtaalamu wa Patrick alimpa amri moja kwa mfuatano wa hasira-kwenda-baarini: kabla hujafungua mlango, sema kwa sauti unachokaribia kufanya.

"Ninaelekea baarini kwa sababu nina hasira."

Hii inafanya kazi kwa sababu msukumo unakunja vitu vitatu — tendo, utaratibu, hisia — katika ishara moja ya kiotomatiki. Sentensi inazifungua tena. Hii haiondoi hasira. Lakini inaondoa kiotomatiki. Na kuondoa kiotomatiki ni nafasi ambayo chaguo tofauti linawezekana.

## Wakati ni Hatua

Kilele cha cortisol kinachozalisha jibu kali zaidi la hasira kwa kawaida humetabolishwa ndani ya dakika ishirini hadi arobaini. Simu ya msaada ya Patrick ilichukua dakika ishirini na tatu. Wakati iliisha, kilele cha kisaikolojia kilikuwa kimepita.
EOT;
$l['key_takeaways'] = json_encode([
    "Before you open the door, say aloud: 'I am about to go to the bar because I am angry.' Decompressing the impulse into words removes automaticity.",
    "Use intermediate space: when the choice is between two unacceptable options (argument or bar), find a third physical location — kitchen, car, staircase.",
    "Time is the intervention. The cortisol spike of peak anger metabolises in 20-40 minutes if you do not fuel it. Your job is to stay put long enough.",
    "Recovery is not about the argument being solved. It is about not going to the bar every time an argument is not solved.",
    "A support call in anger does not need to solve the argument — the listener's job is to carry you through the 20 minutes until the cortisol peak passes."
]);
$l['key_takeaways_sw'] = json_encode([
    "Kabla ya kufungua mlango, sema kwa sauti: 'Ninaelekea baarini kwa sababu nina hasira.' Kufungua msukumo kwa maneno kunaondoa kiotomatiki.",
    "Tumia nafasi ya kati: unapokuwa na chaguo mbili zisizokubalika, tafuta mahali pa tatu pa kimwili.",
    "Wakati ni hatua. Kilele cha cortisol kinaendelea kumetabolishwa ndani ya dakika 20-40. Kazi yako ni kukaa.",
    "Kupona si kuhusu ugomvi ukishughulikiwa. Ni kuhusu kutokwenda baarini kila wakati ugomvi haushughulikiwe.",
    "Simu ya msaada wakati wa hasira haihitaji kutatua ugomvi — kazi ya msikilizaji ni kukubeba kupitia dakika 20."
]);
$lessons[] = $l;

// ── Scenario 5: Six Months ────────────────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-alcohol-six-months';
$l['title']    = 'Scenario: Six Months — and the Unexpected Emptiness';
$l['title_sw'] = 'Hali Halisi: Miezi Sita — na Upweke Usiotarajiwa';
$l['category'] = 'alcohol';
$l['language'] = 'en';
$l['level']    = 'advanced';
$l['duration_minutes'] = 9;
$l['thumbnail_emoji']  = '🎯';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 12;
$l['summary']    = 'Alice reaches six months sober and feels nothing. Not celebration — emptiness. This is the scenario nobody warns you about, and why it is dangerous.';
$l['summary_sw'] = 'Alice anafikia miezi sita bila pombe na kuhisi kitu. Si furaha — upweke. Hii ndiyo hali ambayo hakuna anayekuonya, na kwa nini ni hatari.';
$l['content'] = <<<'EOT'
## Background

Alice is 34. She reaches six months of sobriety on a Wednesday morning in March.

She has been waiting for this milestone. Her therapist mentioned it. Her support group mentioned it. People in recovery talk about six months the way people talk about a destination — like once you get there, something resolves.

She wakes up on Wednesday morning and checks her tracker: 182 days.

She feels nothing. Not relief, not pride, not the sense of arrival she expected. She feels a flat, grey nothing that she does not have a name for.

By 10 AM she is in a mood she does not understand.

---

## What is happening

What Alice is experiencing has a name in recovery literature: the **six-month flatness**. It is common and it is poorly discussed.

Here is what it is:

For the first months of recovery, the project of getting sober provides structure, urgency, and meaning. Every day sober is a fight with a clear enemy. You have something to do: do not drink today. The goal is specific and the feedback is immediate.

At six months, the emergency has passed. The acute withdrawal is long over. The social situations have been navigated. The new habits are established. And now — the project that has been organising your life and your identity for six months is no longer acute. You have done the immediate task. The question that remains is a much larger and quieter one: *Now what?*

This question does not feel triumphant. It feels like standing in a room after a long party has ended and everyone has gone home.

---

## 2:00 PM: The dangerous thought

Alice is at her desk at work. She is supposed to be writing a report. She has not written anything for forty minutes.

The thought arrives without announcement: *"I wonder if I was ever really that bad."*

This is the most dangerous thought in mid-term recovery. It is the beginning of what therapists call **euphoric recall** — the brain's selective retrieval of what alcohol gave (relaxation, social ease, relief) while filtering out what it cost (the mornings, the money, the things said, the relationships eroded, the six months it took to get here).

She sits with this thought. She does not act on it but she does not dismiss it either. She lets it stay.

By 3 PM the thought has expanded slightly: *"Other people drink and manage fine. Maybe I was making it a bigger thing than it was."*

---

## 4:30 PM: She texts her therapist

*"Weird day. Six months today. Feeling flat, not happy. And having some revisionist thoughts about whether it was really as bad as I thought."*

Her therapist replies within twenty minutes:

*"That flat feeling at milestone points is very common — it is sometimes called the 'arrival fallacy.' You expected the milestone to feel like an arrival. Recovery does not feel like that. What you are experiencing is normal and it is also a signal to be careful. Can we speak tomorrow? In the meantime — do not trust those revisionist thoughts. Your journal from month one knows the truth better than your month-six brain does."*

---

## That evening: The journal

Alice goes home and opens her journal from month one. She reads her entry from day three:

*"Cannot sleep. Hands shaking. Told myself I would not touch it again but it is all I can think about. Called a taxi at 2 AM to go and buy. Came back and drank alone in the bathroom so the children would not hear. This is the third time this week. I cannot do this anymore."*

She sits with this for a long time.

Then she reads the entry from day fourteen:

*"First week at work without it. Fell asleep at my desk at 3 PM. Manager asked if I was okay. Said yes. Went home and cried for an hour. This is so hard."*

She closes the journal.

The six-month brain that said "maybe I was not that bad" cannot coexist easily with the day-three brain that drank alone in the bathroom at 2 AM. They are the same person but the day-three brain knew something the six-month brain is trying to forget.

---

## The session: What the therapist explains

The next morning, her therapist explains the six-month territory:

*"The flatness you are feeling is real. It is not a sign that something is wrong with your recovery — it is a sign that you are in the middle of a transition. The emergency phase is over. The question now is: who are you, sober, in ordinary life?"*

*"Most people answer this question slowly, between months six and eighteen. It is less dramatic than early recovery. It looks like: finding things that give you genuine pleasure that are not alcohol. Building relationships that are not organised around drinking. Developing an identity that is not structured around either using or not using."*

*"The revisionist thoughts are part of this territory. They are the brain trying to resolve the dissonance between who you were and who you are becoming. Do not act on them. Do not argue with them either — just notice them and write them down."*

*"And Alice: the flat feeling is not a symptom of failure. It is what the space after emergency feels like. It will not feel like this forever."*

---

## What Alice does with this

She adds a new entry to her journal that evening:

*"Month six. The flatness is a stage, not a verdict. The revisionist thoughts are my brain trying to find an exit from the transition. I do not have to take them seriously. I have to read my month-one journal any time they arrive."*

She sets a recurring reminder on her phone: **Read month one, once a week, until month twelve.**

---

## What this scenario teaches

**Milestones do not feel like arrivals.** Recovery does not deliver a reward at six months that feels proportional to the work. The reward comes slowly and quietly in the months after. Expecting a climax at the milestone and not finding one is disorienting — and disorientation in recovery is a risk factor.

**Euphoric recall is a later-stage risk, not an early-stage one.** Early in recovery, the costs of drinking are very recent. By month six, the brain has had enough distance from the worst nights to begin selectively recalling the good ones. This is neurological and it is not your fault. The protection against it is access to a first-person record from when the costs were still raw.

**The journal is not a wellness exercise — it is evidence.** Alice's month-one journal entries are the most reliable witness to what her drinking actually was. Her month-six brain is not a reliable narrator of those events. The journal is the record that knows the truth.

**"Now what?" is not a failure question.** The question that arrives at six months — *who am I, sober, in ordinary life?* — is not a sign that recovery has stalled. It is the sign that the emergency phase is over and the building phase has begun. It is quieter, less urgent, and ultimately more important.
EOT;
$l['content_sw'] = <<<'EOT'
## Muktadha

Alice ana miaka 34. Anafikia miezi sita ya kujiepusha Jumatano asubuhi Machi.

Amekuwa akingoja alama hii. Anaamka Jumatano asubuhi na kuangalia mfuatiliaji wake: siku 182.

Anahisi kitu. Si utulivu, si kiburi, si hisia ya kufika aliyotarajia. Anahisi upweke wa gorofa, wa kijivu ambao hana jina.

## Kile Kinachoendelea

Kile Alice anapitia kina jina katika fasihi ya kupona: unafsi wa miezi sita.

Kwa miezi ya kwanza ya kupona, mradi wa kuacha pombe unatoa muundo, haraka, na maana. Kila siku bila pombe ni mapigano na adui dhahiri. Una kitu cha kufanya: usinywe leo.

Baada ya miezi sita, dharura imepita. Swali linabaki ni kubwa zaidi na la utulivu zaidi: Sasa nini?

Swali hili halikupi hisia ya ushindi. Linahisi kama kusimama katika chumba baada ya sherehe ndefu kuisha na kila mtu kwenda nyumbani.

## Kile Hali Hii Inafundisha

Alama hazihisi kama kufika. Kupona hakuleti tuzo katika miezi sita inayohisi kulingana na kazi. Tuzo inakuja polepole na kimya katika miezi inayofuata.

Kumbukumbu ya furaha ni hatari ya hatua ya baadaye. Mapema katika kupona, gharama za kunywa ni za hivi karibuni sana. Katika miezi sita, ubongo umepata umbali wa kutosha kutoka usiku mbaya zaidi kuanza kukumbuka mizuri kwa hiari.

Jarida si zoezi la ustawi — ni ushahidi. Maingizo ya jarida la mwezi mmoja wa Alice ni shahidi anayeaminika zaidi wa kunywa kwake kulikuwa kweli nini. Ubongo wake wa miezi sita si msimulizi wa kuaminiwa wa matukio hayo.
EOT;
$l['key_takeaways'] = json_encode([
    "The six-month flatness is common and poorly discussed. The emergency phase ending does not feel triumphant — it feels like an empty room after a party.",
    "Euphoric recall arrives in mid-term recovery: the brain selectively retrieves what alcohol gave while filtering out what it cost. It is neurological, not moral failure.",
    "Your month-one journal is the most reliable witness to what your drinking actually was. Your month-six brain is not a reliable narrator — read the journal when revisionist thoughts arrive.",
    "The question 'was I really that bad?' is the most dangerous thought in mid-term recovery. Do not act on it and do not argue with it — write it down and call your therapist.",
    "The 'now what?' feeling at six months is not a failure question. It signals the emergency phase is over and the building phase — who you are, sober, in ordinary life — has begun."
]);
$l['key_takeaways_sw'] = json_encode([
    "Unafsi wa miezi sita ni wa kawaida na hauzungumzwi vizuri. Dharura kuisha haihisi kama ushindi.",
    "Kumbukumbu ya furaha inakuja katika kupona wa kati: ubongo huchagua kukumbuka kile pombe ilipotoa huku ukichuja kile kilichogharimu.",
    "Jarida lako la mwezi mmoja ni shahidi anayeaminika zaidi wa kunywa kwako kulikuwa kweli nini. Isomee mawazo ya marekebisho yakifika.",
    "Swali 'Je, nilikuwa mbaya kweli?' ni wazo la hatari zaidi katika kupona wa kati.",
    "Hisia ya 'sasa nini?' saa miezi sita si swali la kushindwa. Inaashiria awamu ya dharura imeisha."
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
