<?php
$pdo = new PDO('mysql:host=localhost;dbname=qnztnquh_uberhealth', 'qnztnquh_uberhdb', 'Uber@Health2026!');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$lessons = [];

// ── Lesson 1 ────────────────────────────────────────────────────────────────
$l = [];
$l['slug']    = 'why-saying-no-feels-hard';
$l['title']   = 'Why Saying No Feels Hard';
$l['title_sw'] = 'Kwa Nini Kusema Hapana Ni Vigumu';
$l['summary'] = 'Understand the social, emotional, and neurological forces that make refusal feel so difficult — and why that difficulty is not weakness.';
$l['summary_sw'] = 'Elewa nguvu za kijamii, kihisia, na za neva zinazofanya kukataa kuwe vigumu sana — na kwa nini ugumu huo si udhaifu.';
$l['content'] = <<<'EOT'
## Refusal is harder than it looks

If saying no were easy, everyone in recovery would do it without a second thought. The truth is that refusing a drink, a bet, or a substance is not just a physical act. It is an emotional and social one — and your brain is working against you in that moment.

Understanding why it is hard is the first step to getting better at it.

## Reason 1: The brain's social wiring

Human beings evolved in groups. Acceptance by your group meant survival. Rejection meant danger. That wiring is still active. When you say no to something everyone else is doing, your brain registers a social threat. You may feel a flash of anxiety, embarrassment, or the urge to explain yourself.

This is not weakness. This is your nervous system doing exactly what it was built to do.

## Reason 2: Habit and automatic behaviour

In many situations, using was automatic. You walked into a particular place, sat with particular people, and the behaviour followed without conscious choice. Even after you have decided to stop, your brain still has the old pattern loaded. The cue fires, and the habit response follows — unless you interrupt it deliberately.

## Reason 3: Fear of judgment

They will think I am boring.
They will know something is wrong with me.
They will ask questions I do not want to answer.

These fears are real. They are also usually inaccurate. Research on social perception consistently shows that people pay far less attention to others' choices than we expect. Most people are too focused on themselves to care deeply about whether you are drinking.

## Reason 4: The sunk-cost trap

In the moment, your brain may produce thoughts like: I have already been through so much, one time will not matter. This is the sunk-cost fallacy applied to recovery. The effort you have already put in is not a reason to give in. It is a reason to protect what you have built.

## Reason 5: Ambivalence

Ambivalence is normal in recovery. Part of you wants to refuse. Part of you misses what the substance gave you — relief, belonging, excitement, numbness. Recognising that both parts exist allows you to make a conscious choice, rather than pretending the pull is not there.

## What this means for practice

Refusal is a skill. Like any skill, it is weak the first few times and stronger with practice. The goal of this lesson series is to give you the specific tools — phrases, body language, exit plans — that turn an abstract intention into a reliable behaviour.

You do not have to be confident to use these tools. You only have to use them. Confidence follows.
EOT;
$l['content_sw'] = <<<'EOT'
## Kukataa ni vigumu kuliko inavyoonekana

Kukataa kinywaji, bahati nasibu, au dawa si tendo la kimwili tu. Ni la kihisia na kijamii pia, na ubongo wako unafanya kazi dhidi yako wakati huo.

## Sababu 1: Waya wa kijamii wa ubongo

Binadamu waliibadilika katika makundi. Kukubalika na kundi lako kulimaanisha kuokoka. Hilo linaendelea hadi leo. Unaposema hapana kwa kitu ambacho kila mtu mwingine anafanya, ubongo wako unasajili tishio la kijamii.

## Sababu 2: Tabia na mwenendo wa kiotomatiki

Katika hali nyingi, kutumia kulikuwa kiotomatiki. Ingawa umefanya uamuzi wa kuacha, ubongo wako bado una mfumo wa zamani. Ishara inawasha, na jibu la tabia linafuata, isipokuwa ukiikatiza kwa makusudi.

## Sababu 3: Hofu ya hukumu

Watafikiri mimi ni wa kuchosha. Watauliza maswali nisiyotaka kujibu. Hofu hizi ni za kweli, lakini mara nyingi si sahihi.

## Kukataa ni ustadi

Kukataa ni ustadi. Kama ustadi wowote, ni dhaifu mara za kwanza na unaimarika kwa mazoezi.
EOT;
$l['key_takeaways'] = json_encode([
    "Refusal is hard because of social wiring, habit, fear of judgment, and genuine ambivalence — not weakness.",
    "Your brain registers social threats when you differ from the group. That discomfort is neurological, not personal failure.",
    "Habit patterns fire automatically even after a conscious decision to stop. Interrupting them requires a deliberate tool.",
    "Ambivalence is normal — both parts of you can be present. Acknowledging the pull gives you more control, not less.",
    "Refusal is a skill. It gets easier every time you use it. This lesson series gives you the specific tools."
]);
$l['key_takeaways_sw'] = json_encode([
    "Kukataa ni vigumu kwa sababu ya waya wa kijamii, tabia, hofu ya hukumu, na utata wa kweli, si udhaifu.",
    "Ubongo wako unasajili tishio la kijamii unapotofautiana na kundi. Usumbufu huo ni wa kineva, si kushindwa kibinafsi.",
    "Mifumo ya tabia inawaka kiotomatiki hata baada ya uamuzi wa ufahamu wa kuacha.",
    "Utata ni wa kawaida. Kutambua mvuto kunakupa udhibiti zaidi, si kidogo.",
    "Kukataa ni ustadi. Inakuwa rahisi kila wakati unaoutumia."
]);
$lessons[] = $l;

// ── Lesson 2 ────────────────────────────────────────────────────────────────
$l = [];
$l['slug']    = 'the-stop-technique';
$l['title']   = 'The STOP Technique';
$l['title_sw'] = 'Mbinu ya STOP';
$l['summary'] = 'A four-step method to insert a pause between a trigger and your response — giving your prefrontal cortex time to override the automatic reaction.';
$l['summary_sw'] = 'Njia ya hatua nne ya kuweka pause kati ya kisababishi na jibu lako, ikimpa cortex yako ya prefrontal muda wa kubatilisha majibu ya kiotomatiki.';
$l['content'] = <<<'EOT'
## The problem with automatic responses

When a trigger hits — someone offers you a drink, you walk past a betting shop, stress builds to a familiar level — the response can feel instantaneous. There is barely a gap between the cue and the craving, between the craving and the action.

The STOP technique creates a gap. Four seconds of deliberate action is enough to engage your prefrontal cortex — the brain's decision-making centre — and give you a genuine choice.

## The four steps

**S — Stop**
Do not move. Do not speak yet. Do not reach for anything. Just stop, physically. Even for two seconds. This interrupts the automatic motor sequence before it completes.

**T — Take a breath**
One slow, deliberate breath. Inhale for 4 counts, exhale for 4 counts. This activates the parasympathetic nervous system — the body's braking system. It reduces cortisol and slows the heart rate within seconds. You are now slightly less reactive than you were ten seconds ago.

**O — Observe**
Notice what is happening internally without acting on it:
- What is the urge telling you? What does it want?
- Where do you feel it physically? Chest, stomach, hands?
- What thought or emotion preceded the urge? Stress? Boredom? Social anxiety?
- What does the next two minutes look like if you act on this? What does the next two hours look like?

You are not suppressing anything. You are observing. Observation creates psychological distance.

**P — Proceed mindfully**
Now you make a choice. With the gap you have created, you can proceed with a planned refusal response (from lesson 3), with an exit, or with a coping strategy. You are acting from choice, not from automaticity.

## When to use it

STOP is most useful at the moment of high-risk exposure:
- When someone offers you a substance or invites you to bet
- When you notice a strong craving building
- When you are about to open a betting app or enter a high-risk venue
- Any time you feel the familiar pull of a trigger

## Building the habit

The STOP technique does not work well the first time under high pressure if you have never practised it. Your brain needs to learn the sequence when the stakes are low.

Practise it this week with small decisions: before checking social media, before an impulsive purchase, before a second serving of food. Any automatic behaviour will do.

When you have done it ten times in low-stakes moments, your brain knows the sequence. It will be available when you need it.
EOT;
$l['content_sw'] = <<<'EOT'
## Tatizo na majibu ya kiotomatiki

Mbinu ya STOP inaunda pengo. Sekunde nne za hatua za makusudi zinatosha kushirikisha cortex yako ya prefrontal na kukupa chaguo la kweli.

## Hatua nne

**S — Simama**: Usisogee. Usizungumze bado. Simama tu kimwili.

**T — Toa pumzi**: Pumzi moja polepole ya makusudi. Hii inaanzisha mfumo wa parasympathetic.

**O — Angalia**: Tazama kinachoendelea ndani bila kutenda juu yake. Hamu inakuambia nini? Unasikia wapi kimwili?

**P — Endelea kwa uangalifu**: Sasa unafanya chaguo. Kwa pengo ulilounda, unaweza kuendelea na jibu la kukataa lililopangwa, kuondoka, au mkakati wa kukabiliana.
EOT;
$l['key_takeaways'] = json_encode([
    "The STOP technique creates a 4-second pause that engages the prefrontal cortex before the automatic response completes.",
    "S: Stop physically. T: Take one slow breath (4-4). O: Observe the urge without acting. P: Proceed from choice.",
    "The breath in T directly activates the parasympathetic system — this is physiology, not willpower.",
    "Observation (O) creates psychological distance from the urge without suppressing it.",
    "Practise STOP on low-stakes decisions this week so the sequence is automatic when you need it under pressure."
]);
$l['key_takeaways_sw'] = json_encode([
    "Mbinu ya STOP inaunda pause ya sekunde 4 inayoshirikisha cortex ya prefrontal kabla ya jibu la kiotomatiki kukamilika.",
    "S: Simama kimwili. T: Toa pumzi moja polepole. O: Angalia hamu bila kutenda. P: Endelea kutoka kwa chaguo.",
    "Pumzi katika T inaanzisha moja kwa moja mfumo wa parasympathetic.",
    "Fanya mazoezi ya STOP katika maamuzi ya hatari ndogo wiki hii."
]);
$lessons[] = $l;

// ── Lesson 3 ────────────────────────────────────────────────────────────────
$l = [];
$l['slug']    = 'assertive-refusal-phrases';
$l['title']   = 'Assertive Refusal Phrases';
$l['title_sw'] = 'Misemo ya Kukataa kwa Ujasiri';
$l['summary'] = 'A practical toolkit of tested refusal phrases for every social situation — from a casual first offer to repeated pressure from close friends.';
$l['summary_sw'] = 'Zana za vitendo za misemo ya kukataa iliyojaribiwa kwa kila hali ya kijamii, kutoka kwa toleo la kwanza la kawaida hadi shinikizo la mara kwa mara kutoka kwa marafiki wa karibu.';
$l['content'] = <<<'EOT'
## Why your words matter

The content of a refusal matters less than people think. Research on assertive communication shows that tone, brevity, and body language carry more weight than the specific words. But having tested phrases ready prevents the most common failure: hesitating, over-explaining, or accidentally opening a negotiation.

## The three-tier system

Think of your refusals in three tiers, each escalating in firmness:

### Tier 1: The simple refusal (use first)
Brief, warm, no explanation required.

- No thanks, I am good.
- Not tonight, thanks.
- I will pass on that one.
- Thanks, but no.
- Niko sawa, asante.
- Hapana, asante.

Most offers end here. A confident, unhurried Tier 1 refusal is accepted by most people most of the time. Do not add anything after it. The pause after a refusal feels longer to you than to the other person.

### Tier 2: The firm refusal (use if Tier 1 is pushed)
Slightly more definite, still no lengthy explanation.

- I am not drinking tonight.
- I do not do that anymore.
- I am on a break from that.
- That is not for me right now.
- I have made a decision not to.

Notice: these are statements, not apologies. They do not end with "sorry" or "maybe next time." Those additions signal that you can be persuaded.

### Tier 3: The exit refusal (use when Tier 2 is still not respected)
You are ending the interaction.

- I have said no. I am going to step out for a bit.
- I am not going to change my mind on this. Let us talk about something else.
- I need to go. Good to see you.

Tier 3 is not angry. It is calm and final. You are not punishing them — you are simply protecting yourself.

## Handling specific scenarios

**Someone says: Just one — it will not hurt you.**
Reply: I appreciate that, but no thanks. Then silence.

**Someone asks: You used to — what changed?**
Reply: I am making different choices now. Do not explain further. That question is an invitation to justify yourself. You do not need to.

**Someone asks: Are you in AA or something?**
Reply: I am just not drinking tonight. Keep it present-tense and specific. This usually ends the conversation.

**Someone says: Come on, we are celebrating.**
Reply: I will celebrate with you, just not with that. You are joining the celebration, not rejecting it.

## Body language

55% of communication is non-verbal. Your words are only part of the message.

- Stand straight. Slouching signals uncertainty.
- Eye contact. Hold it naturally — not aggressive, not avoidant.
- Slow delivery. Nervous speech is fast. Confident speech is measured.
- No fidgeting with the glass or object being offered. Do not take it to be polite.
- Smile briefly. Warmth removes the social threat from a refusal.
EOT;
$l['content_sw'] = <<<'EOT'
## Kwa nini maneno yako yanasaidia

Maudhui ya kukataa yanasaidia kidogo kuliko watu wanavyofikiri. Utafiti unaonyesha kwamba sauti, ufupi, na lugha ya mwili vinabeba uzito zaidi kuliko maneno maalum.

## Mfumo wa safu tatu

**Safu 1: Kukataa rahisi**: Niko sawa, asante. Hapana, asante. Fupi, ya joto, hakuna maelezo yanayohitajika.

**Safu 2: Kukataa imara**: Sifanyi hivyo tena. Nimefanya uamuzi wa kutofanya hivyo. Taarifa, si udhuru.

**Safu 3: Kukataa na kuondoka**: Nimesema hapana. Naenda nje kidogo. Utulivu na wa mwisho.

## Lugha ya mwili

Simama wima. Wasiliana kwa macho. Toa polepole. Usiguse kitu kinachotolewa.
EOT;
$l['key_takeaways'] = json_encode([
    "Use a three-tier system: simple refusal first, firm refusal if pushed, exit refusal if still not respected.",
    "Tier 1 phrases are brief, warm, and need no explanation. Most offers end here.",
    "Tier 2 phrases are statements, not apologies. Never end with sorry or maybe next time.",
    "Tier 3 ends the interaction calmly and finally — not angrily.",
    "Body language carries 55% of the message: stand straight, hold eye contact, speak slowly, do not take the offered item."
]);
$l['key_takeaways_sw'] = json_encode([
    "Tumia mfumo wa safu tatu: kukataa rahisi kwanza, imara ukisukumwa, kuondoka ukibaki usisikizwe.",
    "Misemo ya Safu 1 ni mifupi, ya joto, na haihitaji maelezo.",
    "Misemo ya Safu 2 ni taarifa, si udhuru. Kamwe usimalizie na samahani.",
    "Lugha ya mwili inabeba 55% ya ujumbe: simama wima, wasiliana kwa macho, zungumza polepole."
]);
$lessons[] = $l;

// ── Lesson 4 ────────────────────────────────────────────────────────────────
$l = [];
$l['slug']    = 'handling-persistent-pressure';
$l['title']   = 'Handling Persistent Pressure';
$l['title_sw'] = 'Kushughulikia Shinikizo Inayoendelea';
$l['summary'] = 'When one no is not enough — proven techniques for dealing with people who will not accept your refusal, including the broken record method and planned exits.';
$l['summary_sw'] = 'Wakati hapana moja haitoshi — mbinu zilizothibitishwa za kushughulikia watu ambao hawatakubali kukataa kwako, ikiwemo njia ya rekodi iliyovunjika na kutoka kulilopangwa.';
$l['content'] = <<<'EOT'
## When no is not enough

Most offers end at a Tier 1 or Tier 2 refusal. But some people — often those who feel your refusal reflects on them, or who genuinely care about you but show it badly — will push back. Multiple times.

This is not about them being bad people. It is about you having a tool ready for when it happens.

## Technique 1: The broken record

The broken record is the most evidence-based technique for persistent pressure. It involves repeating the same brief refusal phrase, unchanged, without adding new information.

Why it works: Every new reason you give is a new argument they can counter. If you say "I have an early morning" and they say "It is only one drink," you have lost ground. The broken record gives them nothing new to work with.

How to do it:

Them: Come on, just one.
You: No thanks, I am good.
Them: You used to love this.
You: No thanks, I am good.
Them: Do not be boring.
You: No thanks, I am good.

Exactly the same phrase. No explanation. No change in tone. Eventually, most people give up — not because you convinced them, but because there is nothing left to push against.

Your phrase does not have to be "No thanks, I am good." Choose one short phrase that feels natural and memorise it. Use only that one.

## Technique 2: Acknowledge and redirect

For people you care about, pure broken record can feel cold. Acknowledge their intention without opening the door:

"I know you want me to enjoy myself — I am. I am just not drinking tonight."
"I appreciate that you are including me. I am in, just with this" (holding your non-alcoholic drink).

You are affirming the relationship while holding the boundary.

## Technique 3: The planned exit

The most reliable protection against persistent pressure is not to be in the situation longer than necessary. Plan your exit before you arrive:

1. Have a reason ready: "I have got something early" or "I am heading off at 10."
2. Tell one person you trust where you are going when you leave (accountability).
3. Have your transport ready — do not wait for a lift from someone who is drinking.
4. Know the exact words you will say when you leave. Rehearse them.

A planned exit is not failure. It is strategy.

## When the pressure comes from people close to you

When the person pushing is a family member or close friend, the stakes feel higher. Some guidance:

- Do not have the refusal conversation in the moment. Have it separately, in advance, when neither of you is under pressure: "When we go out and I am not drinking, I need you not to push me on it. I will explain more when I am ready, but I need this from you."
- Name what you need specifically: "I need you to order for me without asking" or "I need you not to offer, even once."
- Separate the relationship from the behaviour: "I love you. I just cannot do this particular thing."

## What to do after a difficult refusal situation

When you get through a high-pressure situation without giving in, debrief it — ideally with your therapist or a trusted person:
- What worked?
- What was harder than expected?
- What will you do differently next time?

Each difficult refusal that you survive strengthens the skill for the next one.
EOT;
$l['content_sw'] = <<<'EOT'
## Wakati hapana haitoshi

Watu wengine, mara nyingi wale wanaohisi kukataa kwako kunawarejesha, watasukuma. Mara nyingi.

## Mbinu 1: Rekodi iliyovunjika

Rudia msemo mfupi ule ule wa kukataa, bila kubadilika, bila kuongeza habari mpya. Kila sababu mpya unayotoa ni hoja mpya wanayoweza kupinga. Rekodi iliyovunjika haitoi chochote kipya cha kupigana nacho.

## Mbinu 2: Kutambua na kuelekeza upya

Kwa watu unaowapenda, tambua nia yao bila kufungua mlango: Najua unataka nifurahi, ninafurahi. Sinywei tu usiku huu.

## Mbinu 3: Kutoka kulilopangwa

Ulinzi wa kuaminika zaidi dhidi ya shinikizo inayoendelea ni kutokuwa katika hali hiyo muda mrefu kuliko unavyohitajika. Panga kutoka kwako kabla haujafika.
EOT;
$l['key_takeaways'] = json_encode([
    "The broken record technique: repeat the same brief refusal phrase unchanged, giving nothing new to argue against.",
    "Do not add new reasons — every new reason is a new argument they can counter.",
    "Acknowledge and redirect works for close relationships: affirm the connection while holding the boundary.",
    "Plan your exit before you arrive: have a reason ready, transport arranged, and know your departure words.",
    "For close family or friends, have the conversation in advance — not in the heat of the moment."
]);
$l['key_takeaways_sw'] = json_encode([
    "Mbinu ya rekodi iliyovunjika: rudia msemo ule ule mfupi wa kukataa bila kubadilika.",
    "Usiongeze sababu mpya. Kila sababu mpya ni hoja mpya wanayoweza kupinga.",
    "Panga kutoka kwako kabla haujafika: kuwa na sababu tayari, usafiri umepangwa, na ujue maneno ya kuondoka.",
    "Kwa familia au marafiki wa karibu, fanya mazungumzo mapema, si wakati wa moto."
]);
$lessons[] = $l;

// ── Lesson 5 ────────────────────────────────────────────────────────────────
$l = [];
$l['slug']    = 'branching-decision-trees';
$l['title']   = 'Branching: Decision Trees for Recovery';
$l['title_sw'] = 'Matawi: Miti ya Maamuzi kwa Kupona';
$l['summary'] = 'Build personalised decision trees that map your options at high-risk moments before you face them — so your choices are pre-made, not improvised under pressure.';
$l['summary_sw'] = 'Jenga miti ya maamuzi ya kibinafsi inayoweka ramani ya chaguzi zako wakati wa hatari kubwa kabla hujazikabili, ili maamuzi yako yafanywe mapema, si kwa kubuni chini ya shinikizo.';
$l['content'] = <<<'EOT'
## Why decisions made under pressure are worse

When a craving hits or a trigger fires, your prefrontal cortex — the decision-making, consequence-evaluating part of your brain — becomes less active. Stress hormones flood the system. Your cognitive flexibility narrows. You are, neurologically, less capable of good decision-making in that moment than you were ten minutes earlier.

Branching solves this by doing the decision-making before the crisis, when your prefrontal cortex is fully online.

## What is a decision tree?

A decision tree is a visual map of: If X happens, I will do Y. If Y does not work, I will do Z.

You are not improvising in the moment. You are executing a plan you made when you were calm.

## How to build your recovery decision tree

**Step 1: Name your top three triggers.**
For example: being at a wedding, receiving a large payment, an argument with a family member.

Pick the most frequent or highest-risk one first.

**Step 2: Map the decision points.**
For a wedding scenario, your tree might look like this:

Arrive at wedding
-- Alcohol offered at entrance?
   YES: Use Tier 1 refusal ("No thanks, I am good")
        -- Still pushed?
           YES: Tier 2 ("I am not drinking tonight")
                -- Still pushed?
                   YES: Exit and call your support person
   NO: Continue. Check in with yourself every 30 minutes.

**Step 3: Add your specific phrases.**
Do not leave the branches vague. Write the exact words you will say. This is critical — your brain retrieves specific language, not abstract intentions.

**Step 4: Add your exit trigger.**
At what point will you leave the situation entirely? Define it before you arrive:
- After the meal
- If you are still feeling triggered after 20 minutes
- If a specific person arrives

**Step 5: Debrief after every high-risk event.**
What happened? Which branches did you use? Which worked, which did not? Update the tree.

## The pre-mortem

Before a high-risk event, sit for five minutes and run through your worst-case scenario: What is the most likely way this could go wrong? Then add a branch for exactly that scenario.

This feels pessimistic. It is actually the opposite — you are removing the power of surprise.

## When to use branching

- Before any event that involves your main triggers
- When returning to a place associated with past use
- For any situation you have relapsed in before
- Before family gatherings where pressure is likely

## A note on almost-said-yes moments

If you got through a high-risk moment but it was close — you hesitated, you almost gave in — do not dismiss it. Bring it to your therapist. Those almost-moments are some of the most valuable material for updating your decision tree and strengthening your recovery.
EOT;
$l['content_sw'] = <<<'EOT'
## Kwa nini maamuzi yanayofanywa chini ya shinikizo ni mabaya zaidi

Wakati hamu inakuja, cortex yako ya prefrontal inakuwa na shughuli kidogo. Homoni za msongo zinafurika mfumo. Unyumbukaji wako wa utambuzi unafupika. Kwa kinauro, una uwezo mdogo wa kufanya maamuzi mazuri katika dakika hiyo.

Matawi yanatatua hili kwa kufanya maamuzi kabla ya mgogoro, wakati cortex yako ya prefrontal iko mtandaoni kikamilifu.

## Jinsi ya kujenga mti wako wa maamuzi kwa kupona

1. Taja visababishi vyako vitatu vikuu
2. Weka ramani ya pointi za maamuzi kwa kila kisababishi
3. Ongeza misemo yako maalum — maneno halisi, si nia za kufikirika
4. Ongeza kisababishi chako cha kutoka
5. Fanya tathmini baada ya kila tukio la hatari kubwa
EOT;
$l['key_takeaways'] = json_encode([
    "Under pressure, the prefrontal cortex is less active. Branching does the decision-making before the crisis, not during it.",
    "A decision tree maps: if X happens I will do Y; if Y fails I will do Z — specific phrases, not abstract intentions.",
    "Build a tree for your top three triggers. Start with the most frequent or highest-risk one.",
    "Always include an exit trigger: define in advance the specific point at which you will leave the situation.",
    "Almost-said-yes moments are valuable — bring them to your therapist to update and strengthen your tree."
]);
$l['key_takeaways_sw'] = json_encode([
    "Chini ya shinikizo, cortex ya prefrontal ina shughuli kidogo. Matawi yanafanya maamuzi kabla ya mgogoro.",
    "Mti wa maamuzi unaweka ramani: kama X itatokea nitafanya Y; kama Y itashindwa nitafanya Z.",
    "Jenga mti kwa visababishi vyako vitatu vikuu.",
    "Daima jumuisha kisababishi cha kutoka: fafanua mapema hatua maalum ambayo utaiacha hali hiyo."
]);
$lessons[] = $l;

// ── Run updates ─────────────────────────────────────────────────────────────
$stmt = $pdo->prepare("
    UPDATE lessons SET
        title=:title, title_sw=:title_sw,
        summary=:summary, summary_sw=:summary_sw,
        content=:content, content_sw=:content_sw,
        key_takeaways=:key_takeaways, key_takeaways_sw=:key_takeaways_sw,
        updated_at=NOW()
    WHERE slug=:slug
");

$updated = 0;
$errors = [];
foreach ($lessons as $lesson) {
    try {
        $stmt->execute([
            ':slug'             => $lesson['slug'],
            ':title'            => $lesson['title'],
            ':title_sw'         => $lesson['title_sw'],
            ':summary'          => $lesson['summary'],
            ':summary_sw'       => $lesson['summary_sw'],
            ':content'          => $lesson['content'],
            ':content_sw'       => $lesson['content_sw'],
            ':key_takeaways'    => $lesson['key_takeaways'],
            ':key_takeaways_sw' => $lesson['key_takeaways_sw'],
        ]);
        $updated += $stmt->rowCount();
    } catch (Exception $e) {
        $errors[] = $lesson['slug'] . ': ' . $e->getMessage();
    }
}

// Verify
$rows = $pdo->query("SELECT id, slug, title, category, LENGTH(content) as len FROM lessons WHERE category IN ('refusal','alcohol') ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['updated' => $updated, 'errors' => $errors, 'lessons' => $rows]);
unlink(__FILE__);
