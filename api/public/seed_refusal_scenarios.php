<?php
$pdo = new PDO('mysql:host=localhost;dbname=qnztnquh_uberhealth', 'qnztnquh_uberhdb', 'Uber@Health2026!');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$lessons = [];

// ── Scenario 1: Wedding ───────────────────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-the-wedding';
$l['title']    = 'Scenario: The Wedding';
$l['title_sw'] = 'Hali Halisi: Harusini';
$l['category'] = 'refusal';
$l['language'] = 'en';
$l['level']    = 'beginner';
$l['duration_minutes'] = 8;
$l['thumbnail_emoji']  = '💍';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 6;
$l['summary']    = 'Walk through a real wedding scenario — the offers, the pressure, the almost-moment — and see exactly which technique to use at each step.';
$l['summary_sw'] = 'Pita kupitia hali halisi ya harusi — matoleo, shinikizo, wakati wa karibu — na uone kwa usahihi mbinu gani ya kutumia katika kila hatua.';
$l['content'] = <<<'EOT'
## The situation

Brian has been sober for four months. His cousin is getting married in Kiambu. The whole extended family will be there — uncles he has not seen in two years, old school friends, and the cousin himself who does not know Brian stopped drinking.

It is a Saturday afternoon. The ceremony is done. Now it is the reception, and the waiters are moving through the crowd with trays of Tusker and wine.

This is Brian's highest-risk scenario. He planned for it.

---

## Moment 1: The tray comes by (17 minutes in)

A waiter stops in front of Brian with a tray of drinks. Brian uses the STOP technique before his hand moves.

**What happens inside Brian's head:**
The tray appears. Brian's hand starts to reach — muscle memory from hundreds of similar moments. He catches it. Stops.

He takes one breath. Notices the pull — familiar, in his chest, slightly urgent. Notes it. Does not act on it.

He says: *"No thanks."* The waiter moves on. Done in four seconds.

**What Brian did NOT do:**
- He did not take a glass to look polite and then put it down somewhere
- He did not explain to the waiter that he does not drink
- He did not hesitate long enough for it to become awkward

---

## Moment 2: Uncle James (34 minutes in)

Uncle James appears with two bottles of Tusker — one already open, one still capped. He holds the open one out to Brian.

*"Kijana, leo ni siku ya furaha. Shika hii."*
(Son, today is a happy day. Take this.)

**Brian's inner state:** A different kind of pull. Not just craving — the pull of not wanting to disappoint Uncle James. The pull of belonging.

**Brian applies Tier 1, then broken record:**

"Asante sana mjomba, niko sawa."
(Thank you very much uncle, I am fine.)

Uncle James laughs: *"Sawa? Na kinywaji ukiwa na mkono mkavu?"*
(Fine? And with an empty hand?)

"Niko sawa kweli, asante."

Uncle James waves his hand and turns to the person next to Brian. Done.

**Key observation:** Brian used the same phrase both times. He did not add: "I have an early morning" or "I am driving." Those additions would have invited questions.

---

## Moment 3: The best man (1 hour 10 minutes in)

The best man, Patrick, pulls Brian aside. They were close friends in campus. Patrick has had a few drinks and is in a loud, generous mood.

*"Bro, relax. One drink. I am buying. For my boy's wedding."*

Brian has been at the event for over an hour. He is tired. He is happy, which is its own kind of vulnerable — happy moments trigger as often as stressed ones.

He uses Tier 2 — firm, no explanation:

*"Siwezi, bro. But I am here for the whole evening, let us enjoy it."*

Patrick: *"You sure? Come on—"*

*"Niamini. Niko sawa. Sema, uliwahi ongea na Sarah?"*
(Trust me. I am fine. By the way, did you talk to Sarah?)

The redirect to another topic. Patrick follows it. The conversation moves on.

**What Brian did:** Acknowledged the friendship. Held the boundary. Changed the subject immediately — did not leave space for Patrick to push again.

---

## Moment 4: The almost-moment (2 hours in)

Brian is standing alone for three minutes near the food table. The band is playing. Everyone around him has a drink in their hand. He feels it — not just craving, but something lonelier. The feeling of being outside the moment.

This is the hardest moment of the evening.

**What Brian does:**
He does not white-knuckle it. He texts the one person he told about tonight — his sister:

*"Karibu niingie. Niko sawa. Sitaingia."*
(Almost went in. I am okay. I will not go in.)

She replies: *"Proud of you. Call me when you leave."*

He puts his phone in his pocket. Gets a glass of water. Walks over to where the children are dancing. Stays there for ten minutes.

The moment passes.

---

## What made this work

Brian did three things before the wedding that made all four moments survivable:

1. **He built a decision tree.** He knew the specific scenarios that were most likely (tray passing, Uncle James specifically, the long middle stretch when people are drunk and generous). He had rehearsed his phrases.

2. **He told one person.** His sister knew. That made the text in Moment 4 possible. A support person who does not know cannot help you in real time.

3. **He had an exit plan.** He had agreed with himself: if I feel it escalating past what I can manage, I will say I have to drive someone home and leave. He did not need it, but knowing it was there reduced the pressure of staying.

---

## Your version

Think about your own highest-risk event coming up. Answer:
- Who are the specific people most likely to offer or push?
- What will you say the first time? (Tier 1 phrase)
- What will you say if they push? (Tier 2 phrase / broken record)
- Who is your support contact?
- What is your exit plan?

Write these down before the event. Not in your head. On paper or on your phone. The act of writing makes them more retrievable under pressure.
EOT;
$l['content_sw'] = <<<'EOT'
## Hali Halisi

Brian amekuwa bila pombe kwa miezi minne. Binamu yake anaoa Kiambu. Familia yote ya upanuzi itakuwepo — majomba ambao hawajamwona kwa miaka miwili, marafiki wa zamani wa shule, na binamu mwenyewe ambaye hajui Brian alikomeshwa kunywa.

Ni Jumamosi mchana. Sherehe imekwisha. Sasa ni mapokezi, na wahudumu wanasogea katika umati na trei za Tusker na divai.

Hii ni hali Brian iliyopanga. Alijua inakuja.

## Dakika 17: Trei inakuja

Mhudumu anasimama mbele ya Brian na trei ya vinywaji. Brian anatumia mbinu ya STOP kabla mkono wake haujasogea.

Anasema: Hapana, asante. Mhudumu anaendelea. Imekwisha katika sekunde nne.

## Dakika 34: Mjomba James

Mjomba James anaonekana na chupa mbili. Anamnyoosha moja wazi kwa Brian.

"Kijana, leo ni siku ya furaha. Shika hii."

Brian: "Asante sana mjomba, niko sawa."

Mjomba James: "Sawa? Na kinywaji ukiwa na mkono mkavu?"

Brian: "Niko sawa kweli, asante."

Mjomba James anageuza mkono wake na kugeuka kwa mtu wa karibu na Brian. Imekwisha.

## Masaa 2: Wakati wa karibu

Brian anasimama peke yake kwa dakika tatu karibu na meza ya chakula. Band inacheza. Kila mtu karibu naye ana kinywaji mkononi. Anahisi — si tamaa tu, bali kitu cha upweke zaidi.

Hafanyi kupigana nawe mwenyewe. Anatuma ujumbe kwa dada yake:

"Karibu niingie. Niko sawa. Sitaingia."

Anaweka simu mfukoni. Anapata glasi ya maji. Anaenda mahali ambapo watoto wanacheza. Anakaa huko kwa dakika kumi.

Wakati unapita.
EOT;
$l['key_takeaways'] = json_encode([
    "The hardest moments at events are often not the first offer — they are the lonely middle stretch when everyone around you is drinking.",
    "Using the same refusal phrase twice in a row (broken record) ends most pressure without explanation or argument.",
    "Redirect immediately after Tier 2: change the subject and do not leave space for the other person to re-engage.",
    "Tell one person before the event. A support contact who knows is the difference between surviving the almost-moment and not.",
    "Build your decision tree before you arrive: name the specific people, rehearse the specific phrases, set an exit trigger."
]);
$l['key_takeaways_sw'] = json_encode([
    "Wakati mgumu zaidi katika matukio mara nyingi si toleo la kwanza — ni katikati ya upweke wakati kila mtu karibu nawe anakunywa.",
    "Kutumia msemo ule ule wa kukataa mara mbili mfululizo (rekodi iliyovunjika) kunamaliza shinikizo nyingi bila maelezo.",
    "Elekeza upya mara moja baada ya Safu 2: badilisha mada na usiache nafasi kwa mtu mwingine kushiriki tena.",
    "Mwambie mtu mmoja kabla ya tukio. Mtu wa msaada anayejua ni tofauti kati ya kuokoka na kutookoka."
]);
$lessons[] = $l;

// ── Scenario 2: After Work Drinks ─────────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-after-work-drinks';
$l['title']    = 'Scenario: After-Work Drinks';
$l['title_sw'] = 'Hali Halisi: Vinywaji Baada ya Kazi';
$l['category'] = 'refusal';
$l['language'] = 'en';
$l['level']    = 'beginner';
$l['duration_minutes'] = 7;
$l['thumbnail_emoji']  = '🏢';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 7;
$l['summary']    = 'Friday after a hard week. Your colleagues are going to the bar. You want to be included — just not in the way they expect.';
$l['summary_sw'] = 'Ijumaa baada ya wiki ngumu. Wafanyakazi wenzako wanaenda baarini. Unataka kujumuishwa — si kwa njia wanayotegemea tu.';
$l['content'] = <<<'EOT'
## The situation

Faith has been in recovery from alcohol for seven months. She works in an open-plan office in Westlands. It is Friday at 5:30 PM and her team has just finished a difficult quarter. The manager announces: *"Watu, twende Brew Bistro. Company inanunua."*

Six of her eight colleagues immediately start reaching for their bags.

Faith's history: she used to be the one who organised these outings. The bar was where she built friendships in this job. It is not just a craving risk — it is an identity question. The person she used to be went to every Friday outing.

---

## The decision point (5:31 PM)

Faith has about sixty seconds before the group starts moving and she has to either go with them or explain herself.

She has three options:

**Option A: Go, and manage it there.**
Risk: high. She has been to this bar before while in recovery. The last time, she had three drinks. The familiarity of the venue, the social comfort, the relaxed Friday energy — it is a convergence of her strongest triggers.

**Option B: Decline, go home, feel isolated.**
Risk: moderate. Technically safe, but the isolation and the sense of missing out can drive her to drink alone later — which has happened before.

**Option C: Go, with a plan.**
She goes. She orders a soda water with lime in a tall glass. She does not explain to anyone what is in the glass unless asked directly. She sets an exit time of 7:30 PM. She has her support person's number ready.

She chooses Option C.

---

## At the bar: First challenge (6:05 PM)

The first round arrives. Her manager puts a beer in front of her without asking.

*"Madam, umechoka. Hii itakusaidia."*

Faith picks up the glass and sets it aside. She holds her soda water.

*"Nimeshachukua, boss. Asante."*
(I have already got one, boss. Thank you.)

The manager glances at her drink, nods, turns back to the conversation. Done.

**The technique:** She did not refuse the beer loudly in front of the group. She displaced it quietly and showed she already had a drink. This is the strategy of substitution — always have something in your hand. It eliminates 80% of offers before they are made.

---

## Second challenge (6:40 PM)

Her colleague Dennis orders a second round and calls across the table: *"Faith! Unanywa nini? Beer ama wine?"*

The table turns to look at her.

This is the moment that feels most dangerous — not because she wants to drink, but because of the spotlight. She has two seconds before the pause becomes awkward.

*"Niko sawa, hii bado ipo."* (I am fine, I still have this.)

She holds up her glass and turns immediately to the colleague on her left: *"Uliambia kuhusu deal ile ya Mombasa — ilikuwaje?"*

The table conversation fragments back into side conversations. Nobody follows up on what was in her glass.

**What made this work:** She responded, showed evidence (the glass), and redirected in one motion. No gap for follow-up. The redirect question was genuine — she was actually curious about the Mombasa deal — so it did not feel like a deflection.

---

## The long middle (7:00 PM – 7:20 PM)

People are loosening up. The conversation gets louder. Someone orders shots. The shots go around. When one reaches Faith she passes it to the person next to her without taking it.

"Shikanisha kwa David."
(Pass it to David.)

No explanation. David takes it. The shot moves on.

This moment is passed without a word of refusal. Just a physical gesture.

---

## Exit (7:28 PM)

It is two minutes before her self-set exit time. She finishes what is in her glass. She stands and picks up her bag.

*"Watu, lazima niende. Iko early morning kesho. Mfanye vizuri."*
(People, I have to go. I have an early morning tomorrow. Have a good one.)

Two people say: *"Mapema sana! Kaa kidogo."*

Faith smiles: *"Next time. Leo siwezi."* She hugs the nearest person and walks out.

**She does not apologise. She does not explain in detail. She leaves when she said she would leave.**

---

## What Faith brought to this evening

Before she left the office:
- She decided: Option C.
- She identified the two riskiest people (Dennis and the manager) and rehearsed the responses.
- She told her sister she was going and would text when she left.
- She set 7:30 as her exit time and committed to it regardless of how the evening felt.

None of those preparations were visible to anyone at the bar. But they were the reason she got through it.

---

## The thing to notice

Faith was not white-knuckling all evening. By 6:45 she was genuinely enjoying the conversation. Recovery does not require suffering through social events — it requires being strategic about the entry and exit points, and having tools for the three or four moments in between that actually test you.
EOT;
$l['content_sw'] = <<<'EOT'
## Hali Halisi

Faith amekuwa katika kupona kutoka kwa pombe kwa miezi saba. Anafanya kazi Westlands. Ni Ijumaa saa kumi na moja na timu yake imekamilisha robo ngumu. Meneja anatangaza: "Watu, twende. Kampuni inanunua."

Faith alikuwa yeye ambaye aliandaa safari hizi. Baa ilikuwa mahali alipojengea urafiki. Si hatari ya hamu tu — ni swali la utambulisho.

## Hatua ya Uamuzi

Faith ana chaguo tatu: kukataa na kwenda nyumbani, kwenda na hatari ya juu, au kwenda na mpango.

Anachagua kwenda na mpango: anaagiza maji ya soda na chokaa katika glasi ndefu, anaweka wakati wa kuondoka wa 7:30 PM, ana nambari ya mtu wa msaada tayari.

## Mkakati Muhimu: Uingizwaji

Daima kuwa na kitu mkononi. Inazuia 80% ya matoleo kabla hayajafanywa.

## Kile Alicholeta

Kabla ya kuondoka ofisini:
- Aliamua: Chaguo C.
- Alitambua watu wawili wenye hatari zaidi na akafanya mazoezi ya majibu.
- Aliweka 7:30 kama wakati wake wa kuondoka.

Hakuna kati ya maandalizi hayo yaliyoonekana kwa mtu yeyote baarini. Lakini yalikuwa sababu aliyopitia.
EOT;
$l['key_takeaways'] = json_encode([
    "Always have a drink in your hand at bars. It eliminates most offers before they are made.",
    "Substitution + redirect is a two-move sequence: show your existing drink, immediately change the subject.",
    "Set a specific exit time before you arrive and leave at that time regardless of how the evening is going.",
    "You do not have to tell people what is in your glass. A soda water with lime in a tall glass looks exactly like a drink.",
    "Recovery does not require suffering through social events — it requires tools for the 3-4 testing moments inside them."
]);
$l['key_takeaways_sw'] = json_encode([
    "Daima kuwa na kinywaji mkononi kwenye baa. Inazuia matoleo mengi kabla hayajafanywa.",
    "Uingizwaji + kuelekeza upya ni mfuatano wa mwendo mbili: onyesha kinywaji chako kilichopo, badilisha mada mara moja.",
    "Weka wakati maalum wa kuondoka kabla hujaenda na uondoke wakati huo bila kujali jinsi jioni inavyokwenda.",
    "Kupona hakuhitaji kuteseka katika matukio ya kijamii — kunahitaji zana kwa wakati wa majaribio 3-4 ndani yake."
]);
$lessons[] = $l;

// ── Scenario 3: The Payday Gambling Urge ─────────────────────────────────
$l = [];
$l['slug']     = 'scenario-payday-gambling-urge';
$l['title']    = 'Scenario: Payday and the Gambling Urge';
$l['title_sw'] = 'Hali Halisi: Siku ya Malipo na Hamu ya Kucheza';
$l['category'] = 'refusal';
$l['language'] = 'en';
$l['level']    = 'intermediate';
$l['duration_minutes'] = 8;
$l['thumbnail_emoji']  = '💰';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 8;
$l['summary']    = 'End of month. Money is in. The urge hits with a specific logic — "I can afford to lose this." Walk through exactly how to interrupt it.';
$l['summary_sw'] = 'Mwisho wa mwezi. Pesa imeingia. Hamu inagonga kwa mantiki maalum — "Naweza kumudu kupoteza hii." Pita kupitia jinsi ya kuikatiza.';
$l['content'] = <<<'EOT'
## The situation

Samuel is 31, a sales representative in Nairobi. He has been in recovery from gambling for five months. His highest-risk day has always been the last Friday of the month — when salary arrives.

It is 3:47 PM. His phone vibrates. The bank notification confirms: salary received.

The thought comes immediately, before he has even unlocked his phone properly:

*"Today I can afford to lose two thousand. Just two thousand. That is not serious money."*

This is the most dangerous thought in gambling recovery. It sounds reasonable. That is why it is dangerous.

---

## Anatomy of the payday thought

Let us break down what that thought is actually doing:

**"Today I can afford to lose two thousand."**
- This assumes a loss in advance — which means part of Samuel already knows what is likely to happen.
- It sets a loss limit of KES 2,000 — a limit that has never once held in his history.
- The word "lose" is framed as affordable, not as a problem. This is normalisation language.

**"Just two thousand."**
- The word "just" is minimisation. It is the same word alcoholics use: "just one drink."
- KES 2,000 at the end of a session has historically become KES 8,000 or KES 15,000 for Samuel.

**"That is not serious money."**
- This is true and false simultaneously. KES 2,000 is not catastrophic. But it is the first step on a path Samuel has walked many times to a destination he knows well.

---

## Moment 1: STOP (3:48 PM)

Samuel does not unlock his phone yet. He sits with it in his hand.

He has one minute in the matatu before his stop. He uses it.

**S — Stop.** He puts the phone screen-down on his lap.

**T — Breath.** One slow inhale. He notices his hands — slightly warmer than usual. A physical tell he has learned to recognise.

**O — Observe.** The thought is still there. He does not fight it. He names it: *"The payday thought is here. This is what it always sounds like."* Naming it creates distance. It becomes an event he is watching, not one he is inside.

**P — Proceed.** He texts his accountability contact — a friend from his support group:

*"Salary came in. Payday thought. Niko."*
(Salary came in. Payday thought. I am here / I am okay.)

His contact replies in two minutes: *"Received. Unaenda wapi saa hii?"*
(Received. Where are you going right now?)

---

## The vulnerability window

Samuel knows from experience that the vulnerability window on payday lasts approximately four hours — from when the money arrives until he has converted it into specific obligations. Once KES 15,000 is mentally assigned to rent, transport, and food, the "loose money" feeling disappears.

His strategy is to close the window fast:

3:50 PM — He opens his banking app and immediately transfers rent to his landlord's account. Then airtime. Then savings. He leaves only what he needs for the weekend in his spending account.

This takes seven minutes. When he is done, the "I can afford to lose two thousand" thought has lost its financial foundation. There is no two thousand to lose.

**This is environmental control** — the most reliable form of protection. Willpower is unreliable under craving. Removing access to the resource removes the need for willpower entirely.

---

## Moment 2: The WhatsApp message (5:15 PM)

He is home. A WhatsApp message from a contact he should have blocked six months ago:

*"Buda, leo imeingia? Kuna mchezaji anakusubiri."*
(Brother, did it come in today? There is a game waiting for you.)

His body responds before his mind processes the words — a flush of excitement mixed with dread. He recognises the feeling.

He does not reply. He does not open the conversation to see if there are more messages. He blocks the number.

Then he sits with the feeling for three minutes. It does not require action. It is just a feeling.

**What he does not do:** He does not reply "No thank you." He does not explain himself to this person. In gambling recovery, engagement with invitations — even to decline politely — keeps the door open. The answer is silence and a block.

---

## 8:00 PM: Reviewing the day

Samuel has dinner and calls his sister. He tells her the salary came in and he is okay. She congratulates him.

He opens his recovery journal and writes three sentences:
- The payday thought came at 3:48 PM on the bus.
- I used STOP and texted support. I transferred obligations before anything else.
- Hardest moment: the WhatsApp at 5:15. I blocked without replying.

He closes the journal.

---

## What makes payday different

Payday is not a random trigger. It is a predictable, recurring, high-risk event that arrives on a known schedule. This means it can be prepared for in advance:

- The week before payday: remind your support person that payday is coming.
- On payday morning: plan the vulnerability window — where will you be, what will you do with the first hour after the notification?
- Transfer obligations before touching anything else. Make the "loose money" disappear.
- Block or mute contacts who are linked to gambling for the first 48 hours after payday.

Preparation does not make payday easy. It makes it survivable with your recovery intact.
EOT;
$l['content_sw'] = <<<'EOT'
## Hali Halisi

Samuel ana miaka 31, mwakilishi wa mauzo Nairobi. Amekuwa katika kupona kutoka kwa kamari kwa miezi mitano. Siku yake ya hatari kubwa zaidi imekuwa daima Ijumaa ya mwisho ya mwezi — wakati mshahara unaingia.

Ni 3:47 PM. Simu yake inatetemeka. Arifa ya benki inathibitisha: mshahara umepokelewa.

Wazo linakuja mara moja: "Leo ninaweza kumudu kupoteza elfu mbili. Elfu mbili tu. Hiyo si pesa kubwa."

Hii ndiyo wazo la hatari zaidi katika kupona kutoka kwa kamari. Inasikika kama ya busara. Ndiyo maana ni hatari.

## Mkakati Muhimu: Udhibiti wa Mazingira

Uaminifu ni usio wa kuaminika chini ya hamu. Kuondoa ufikiaji wa rasilimali kunaondoa haja ya nguvu za mapenzi kabisa.

Samuel anafungua app yake ya benki na mara moja anahamisha kodi kwa akaunti ya mmiliki wake. Kisha airtime. Kisha akiba. Anacha tu anachohitaji kwa wikendi katika akaunti yake ya matumizi.

Inachukua dakika saba. Wazo la "ninaweza kumudu kupoteza elfu mbili" limepoteza msingi wake wa kifedha.

## Kujilinda kwa Siku ya Malipo

Wiki kabla ya siku ya malipo: mkumbushe mtu wako wa uwajibikaji.
Asubuhi ya siku ya malipo: panga dirisha la mazingira magumu.
Hamisha wajibu kabla ya kugusa chochote kingine.
Zuia au nyamazisha mawasiliano yanayohusiana na kamari kwa saa 48 za kwanza.
EOT;
$l['key_takeaways'] = json_encode([
    "The payday thought always sounds reasonable — that is what makes it dangerous. Name it, do not argue with it.",
    "The word 'just' in gambling recovery means the same as 'just one drink' in alcohol recovery — it has never held.",
    "Environmental control beats willpower: transfer obligations immediately after payday so loose money does not exist.",
    "Do not reply to gambling invitations — not even to decline. Silence and a block is the complete answer.",
    "Payday is predictable. Prepare the week before: alert your support person, plan the vulnerability window."
]);
$l['key_takeaways_sw'] = json_encode([
    "Wazo la siku ya malipo linasikika kama la busara daima — ndiyo kinachofanya liwe hatari. Litaje, usijadiliane nalo.",
    "Neno 'tu' katika kupona kutoka kwa kamari linamaanisha sawa na 'kinywaji kimoja tu' katika kupona kutoka kwa pombe.",
    "Udhibiti wa mazingira unashinda nguvu za mapenzi: hamisha wajibu mara moja baada ya siku ya malipo.",
    "Usijibu mialiko ya kamari — hata kukataa. Ukimya na kuzuia ni jibu kamili."
]);
$lessons[] = $l;

// ── Scenario 4: Family Gathering ─────────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-family-gathering';
$l['title']    = 'Scenario: The Family Gathering';
$l['title_sw'] = 'Hali Halisi: Mkutano wa Familia';
$l['category'] = 'refusal';
$l['language'] = 'en';
$l['level']    = 'intermediate';
$l['duration_minutes'] = 9;
$l['thumbnail_emoji']  = '🏡';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 9;
$l['summary']    = 'Family events are uniquely difficult — the people pushing you are the ones you love most, and they may not know about your recovery at all.';
$l['summary_sw'] = 'Matukio ya familia ni magumu kipekee — watu wanaokusukuma ni wale unaowapenda sana, na wanaweza wasijue kabisa kuhusu kupona kwako.';
$l['content'] = <<<'EOT'
## The situation

Grace is 27. She has been in recovery from alcohol for nine months. Her family does not know. She told no one at home because she is not ready for the questions, the worry, or her mother's particular way of making everything about herself.

It is Easter Sunday. The family is at her aunt's house in Githurai. There will be fifteen adults, food, music, and — because her uncle bought two crates last week — a lot of beer.

Grace has not been to a family gathering since she stopped. This is the first one.

---

## Before she arrives: The conversation she never had

Grace's therapist suggested, three weeks ago, that Grace tell at least one family member before the Easter gathering. Grace chose not to. This is her choice to make.

But the therapist was right that going in with zero support inside the event is a higher-risk configuration. Grace compensates by:

1. Driving herself (exit available at any moment, no negotiation)
2. Texting her therapist before she goes in: *"Entering. Back to you at 4."*
3. Setting 4:00 PM as her exit time
4. Deciding in advance to tell anyone who asks directly: *"Ninapumzika kutoka kwa pombe kwa sasa."* (I am taking a break from alcohol for now.)

That phrase — "for now" — is intentional. It is honest and it does not invite the follow-up questions that "I am in recovery" sometimes triggers.

---

## Arrival (1:10 PM)

Her aunt's house. The smell of nyama choma. Cousins she has not seen since Christmas.

Within four minutes, a cousin puts a cold Tusker in her hand. She does not take it into her palm — she deflects it sideways, touching only the rim.

*"Acha nimalize maji yangu kwanza."*
(Let me finish my water first.)

She walks to the kitchen. She pours herself a glass of juice. She does not come back out to the sitting room for six minutes. When she does, the cousin is deep in another conversation and has forgotten about the beer entirely.

---

## Her mother (1:50 PM)

Grace's mother corners her near the food table: *"Grace, unakaa hujanywa. Kila mtu ana kitu. Hii inakufanya uonekane vibaya."*
(Grace, you look like you have not had a drink. Everyone has something. This makes you look bad.)

This is the hardest kind of pressure: wrapped in concern, actually about social appearance, and coming from someone Grace cannot simply brush off.

**Grace's response:** She does not defend herself. She does not argue. She does not explain her recovery.

*"Mama, niko sawa. Nifurahie leo? Nimekuona zaidi ya miaka."*
(Mum, I am fine. Let me enjoy you today? I have seen you in over a year.)

She touches her mother's arm and steers her toward where her uncle is doing something funny with a spoon. Her mother laughs and follows.

**The technique:** Redirect toward the relationship, not the conflict. Grace could have argued about what "looking bad" means or explained that she does not drink anymore. Instead she named what she actually wants — to enjoy time with her mother — and moved physically toward something positive.

---

## The question (3:15 PM)

Her uncle — not an unkind man, just direct — asks openly in front of four people:

*"Grace, tangu ulipofika hujashika chupa. Umekuwa mgeni mara ngapi bila kunywa? Una ujauzito?"*
(Grace, since you arrived you have not touched a bottle. How many times have you been a guest without drinking? Are you pregnant?)

Laughter around the table.

Grace has prepared for this. She smiles:

*"Hapana mjomba. Ninapumzika tu kwa sasa. Fanya ile nyama ilete hapa — inakaa vizuri."*
(No uncle. I am just taking a break for now. Bring that meat here — it looks good.)

She reaches across for the serving tray. Her uncle shrugs and turns to the person next to him. The conversation moves on.

**What she used:** The prepared "taking a break for now" phrase. A redirect to food — a universal distractor in Kenyan family settings. No defensiveness, no over-explaining. A smile that signalled: this is not a big deal, so let us not make it one.

---

## 3:58 PM — Two minutes before exit

Grace has made it to within two minutes of her self-set departure time. She is okay. She is actually glad she came. She says goodbye to her grandmother, her aunt, her mother.

Her mother: *"Unaenda tayari? Utarudi na nini?"*
(You are leaving already? What are you coming back with?)

*"Mama, nitakupigia simu kesho. Nakupenda."*

She hugs her and walks to her car.

---

## The debrief

That evening Grace texts her therapist: *"Made it. Left at 4. Used 'taking a break for now' twice. Hardest moment was mum at the food table. Did not tell anyone. Think I can tell my sister next time."*

That last sentence — *"think I can tell my sister next time"* — is progress. Nine months in, she is one step closer to having support inside the event rather than just outside it.

---

## What family gatherings require

Family gatherings are difficult for a specific reason: the people most likely to push are the people whose opinion of you matters most. This means the emotional cost of each refusal is higher than with strangers.

Two things help:

1. **A prepared phrase that does not invite debate.** "I am taking a break for now" is specific enough to be credible, vague enough not to trigger a family intervention, and short enough that it lands and ends.

2. **A predetermined exit and someone outside the event who knows you are there.** You do not need support inside the room if you have a clear door and someone waiting for your text.
EOT;
$l['content_sw'] = <<<'EOT'
## Hali Halisi

Grace ana miaka 27. Amekuwa katika kupona kutoka kwa pombe kwa miezi tisa. Familia yake haijui. Hakumwambia mtu nyumbani kwa sababu hayu tayari kwa maswali.

Ni Jumapili ya Pasaka. Familia iko nyumba ya shangazi yake Githurai. Kutakuwa na watu kumi na watano wazima, chakula, muziki, na — kwa sababu mjomba wake alinunua sanduku mbili wiki iliyopita — bia nyingi.

## Msemo Uliotayarishwa

Grace aliamua mapema kumwambia mtu yeyote anayeuliza moja kwa moja: "Ninapumzika kutoka kwa pombe kwa sasa."

Msemo huo — "kwa sasa" — ni wa makusudi. Ni wa uaminifu na hauaprichi maswali ya ufuatiliaji ambayo "Niko katika kupona" wakati mwingine husababisha.

## Kinachofanya Mikutano ya Familia Kuwa Vigumu

Mikutano ya familia ni vigumu kwa sababu maalum: watu wanaosukuma zaidi ni watu ambao maoni yao yanakusumbua zaidi.

Mambo mawili husaidia:
1. Msemo uliotayarishwa ambao hauaprichi mjadala.
2. Kutoka kulikowekwa mapema na mtu nje ya tukio anayejua uko huko.
EOT;
$l['key_takeaways'] = json_encode([
    "Family pressure is harder than stranger pressure because the emotional cost per refusal is higher — the opinion matters more.",
    "Prepare a single phrase: 'I am taking a break for now' is credible, non-specific, and short enough to land and end.",
    "Redirect toward the relationship, not the conflict: name what you want (time with this person) and move physically toward something positive.",
    "Drive yourself. Having your own exit available at any moment changes the psychological calculation of the entire event.",
    "Support outside the room is sufficient if you have a clear door and someone waiting for your text."
]);
$l['key_takeaways_sw'] = json_encode([
    "Shinikizo la familia ni gumu zaidi kuliko shinikizo la wageni kwa sababu gharama ya kihisia kwa kila kukataa ni kubwa zaidi.",
    "Tayarisha msemo mmoja: 'Ninapumzika kwa sasa' unaaminiwa, si maalum, na mfupi wa kutosha kuwekwa na kuisha.",
    "Elekeza upya kuelekea uhusiano, si mgongano: taja unachotaka na sogea kimwili kuelekea kitu chanya.",
    "Endesha gari lako mwenyewe. Kuwa na kutoka kwako kunapatikana wakati wowote kunabadilisha hesabu ya kisaikolojia."
]);
$lessons[] = $l;

// ── Scenario 5: The Relapse Almost ────────────────────────────────────────
$l = [];
$l['slug']     = 'scenario-the-almost-relapse';
$l['title']    = 'Scenario: The Night It Almost Happened';
$l['title_sw'] = 'Hali Halisi: Usiku Ulipokaribia Kutokea';
$l['category'] = 'refusal';
$l['language'] = 'en';
$l['level']    = 'advanced';
$l['duration_minutes'] = 10;
$l['thumbnail_emoji']  = '🌙';
$l['is_premium']  = 0;
$l['is_published'] = 1;
$l['order'] = 10;
$l['summary']    = 'The night James almost relapsed after six months clean. What he felt, what he did, and what the next morning showed him about how close he actually came.';
$l['summary_sw'] = 'Usiku James alipokaribia kurudi nyuma baada ya miezi sita safi. Alichohisi, alichofanya, na kilichomwonyesha asubuhi ya pili jinsi alivyokuja karibu kweli.';
$l['content'] = <<<'EOT'
## What happened

James is 35. Six months and eleven days clean from alcohol. He works in logistics and lives alone in a bedsitter in Ruaka. By any measure, the last six months have been his best stretch of sobriety.

Then his employer called him at 4:30 PM to say his contract would not be renewed past the end of the month.

He sat with that news for four hours before he picked up his phone.

---

## 8:45 PM: The progression

He had been eating nothing since 2 PM. He had not called anyone. He had not texted his sponsor. He had sat in his bedsitter watching football — or appearing to watch it — while the news settled into him.

By 8:45 PM, the quality of his thinking had changed in a way he did not immediately notice.

**Thought 1** (probably around 6 PM, not noticed): *"This is a lot to deal with."* True. Appropriate.

**Thought 2** (around 7 PM, not noticed): *"I have been so careful for six months and this is what I get."* Still true. Starting to recruit grievance.

**Thought 3** (around 8 PM, not noticed): *"I have earned a break from being careful."* This is the thought that should have triggered an alarm. It did not.

**Thought 4** (around 8:45 PM, noticed): *"I am going to walk to the kiosk."*

He stood up.

---

## The pause that changed the night

He was standing at his door with his shoes on when his phone lit up on the table. His recovery group chat — someone posting a check-in he had almost missed.

He stopped. Not because of the message content, but because picking up his phone made him look at the time. 8:52 PM. He had been sitting alone, not eating, not calling anyone, for over four hours since bad news.

Something clicked.

He sat back down. He put his shoes on the floor. He sat on the bed.

He did not use the STOP technique deliberately. He fell into the pause accidentally. But the pause was enough.

For about ninety seconds he just sat there. No technique. No protocol. Just sat.

Then he called his older brother.

---

## The call (8:55 PM)

His brother picked up on the second ring. James said: *"Nimepoteza kazi. Nilikuwa nataka kwenda kiosk. Nilikaa chini."*
(I lost my job. I was about to go to the kiosk. I sat back down.)

His brother: *"Uko wapi sasa?"*
(Where are you now?)

*"Kwa nyumba."*
(At home.)

*"Sawa. Kaa. Nitakupigia simu baada ya dakika kumi — ninamalizia kitu."*
(Okay. Stay. I will call you back in ten minutes — I am finishing something.)

James sat. His brother called back in eight minutes. They talked for forty-five minutes. At the end of the call James's brother said he would come by in the morning.

James went to sleep at 11:15 PM. He did not go to the kiosk.

---

## The morning debrief

The next morning, James talked to his therapist. She asked him to map the progression — when did each thought arrive, and when did he notice it.

What he found:

The dangerous thought — *"I have earned a break from being careful"* — had arrived approximately ninety minutes before he stood up to leave. He had not noticed it consciously. It had been producing his emotional state for an hour and a half before it converted into an intention to act.

His therapist named this: **cognitive erosion.** Under sustained stress, the protective thoughts (reasons not to drink) erode first. The craving thoughts (reasons to drink) remain. By the time the person notices, the field is already tilted.

---

## What James learned

**About the almost-moment:**
The phone lighting up was accidental. The pause that followed was accidental. He was closer to relapse than he understood in the moment. The next time the conditions converge — bad news, alone, not eating, not calling anyone, hours passing — he will need to act earlier in the progression.

**About the four-hour window:**
Not calling anyone for four hours after bad news is itself the failure point — not the moment at the door. By the time he was standing with his shoes on, his thinking had already been eroded. The intervention needed to happen at hour one, not hour four.

**His new rule:**
After any significant bad news, he calls someone within thirty minutes. Not when he feels like he needs to. Within thirty minutes. Before the erosion has time to work.

---

## What this scenario teaches

Recovery does not fail all at once. It fails slowly, through a sequence of small unnoticed decisions: not eating, not calling, letting the thoughts go unchallenged, waiting to feel bad enough before reaching out.

The relapse almost always begins hours before the almost-moment you can see.

The single most powerful protective action is early contact — not when you are standing at the door with your shoes on, but when the news first arrives and you know this is going to be a difficult night.

The tools work best when used early. The broken record, the STOP technique, the planned exit — all of these are downstream tools. They catch you at the door. The upstream protection is simpler: do not be alone for four hours after hard news.
EOT;
$l['content_sw'] = <<<'EOT'
## Kilichotokea

James ana miaka 35. Miezi sita na siku kumi na moja safi kutoka kwa pombe. Kisha mwajiri wake alimpigia simu saa kumi na nusu asubuhi kumwambia mkataba wake hautafanywa upya.

Alikaa na habari hizo kwa masaa manne kabla ya kuchukua simu yake.

## Mmomonyoko wa Utambuzi

Chini ya msongo unaoendelea, mawazo ya kinga (sababu za kutokunywa) yanaeromoka kwanza. Mawazo ya hamu (sababu za kunywa) yanabaki. Wakati mtu anapoona, uwanja tayari umeinamia.

James alijifunza kwamba muda wa hatari ulikuwa saa nne za kukaa peke yake bila kula, bila kumpigia mtu simu — si wakati alipokuwa amesimama mlangoni na viatu vyake.

## Kanuni Yake Mpya

Baada ya habari mbaya yoyote kubwa, anampigia mtu simu ndani ya dakika thelathini. Si wakati anahisi anahitaji. Ndani ya dakika thelathini. Kabla ya mmomonyoko kufanya kazi.

## Kile Hali Hii Inafundisha

Kupona haishindwi yote mara moja. Inashindwa polepole, kupitia mfuatano wa maamuzi madogo yasiyoonekana.

Kulasa karibu huanza kila wakati masaa kabla ya wakati wa karibu unaoweza kuona.

Hatua moja ya kinga yenye nguvu zaidi ni mawasiliano ya mapema — si ukiwa unasimama mlangoni na viatu vyako, bali wakati habari zinafika kwanza.
EOT;
$l['key_takeaways'] = json_encode([
    "Cognitive erosion: under sustained stress, protective thoughts erode before craving thoughts do. By the time you notice, the field is already tilted.",
    "The relapse almost always begins hours before the almost-moment you can see. Watch the four-hour warning window.",
    "Not calling anyone for four hours after bad news is the failure point — not the moment you are standing at the door.",
    "Early contact is the most powerful upstream protection: call someone within 30 minutes of significant bad news, not when you feel bad enough.",
    "The STOP technique and refusal phrases are downstream tools — they catch you at the door. Upstream protection is simpler: do not be alone with hard news."
]);
$l['key_takeaways_sw'] = json_encode([
    "Mmomonyoko wa utambuzi: chini ya msongo, mawazo ya kinga yanaeromoka kwanza. Ukiwaona, uwanja tayari umeinamia.",
    "Kulasa karibu huanza masaa kabla ya wakati unaoweza kuona.",
    "Kutompigia mtu simu kwa masaa manne baada ya habari mbaya ndiyo hatua ya kushindwa — si wakati unasimama mlangoni.",
    "Mawasiliano ya mapema ni ulinzi wa juu zaidi: mpigie mtu simu ndani ya dakika 30 za habari mbaya kubwa.",
    "Mbinu za STOP na misemo ya kukataa ni zana za chini. Ulinzi wa juu ni rahisi zaidi: usiwe peke yako na habari ngumu."
]);
$lessons[] = $l;

// ── Insert all ────────────────────────────────────────────────────────────
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

$rows = $pdo->query("SELECT id, slug, title, category, LENGTH(content) as len FROM lessons WHERE category='refusal' ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['inserted' => $inserted, 'errors' => $errors, 'refusal_lessons' => $rows]);
unlink(__FILE__);
