"""Run: python seed_lessons.py — seeds 20 lessons in EN + SW."""
import os, django
os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'config.settings.development')
django.setup()
from apps.lessons.models import Lesson

lessons = [
    # ── DEPRESSION ───────────────────────────────────────────────────────
    {
        'title': 'Understanding Depression',
        'title_sw': 'Kuelewa Unyogovu',
        'slug': 'understanding-depression',
        'category': 'depression',
        'level': 'beginner',
        'summary': 'Learn what depression is, how it affects your brain, and why it is not a character weakness.',
        'summary_sw': 'Jifunza unyogovu ni nini, jinsi unavyoathiri ubongo wako, na kwa nini si udhaifu wa tabia.',
        'content': '''## What is Depression?

Depression is a common and serious medical condition that negatively affects how you feel, think, and act. It causes feelings of sadness and a loss of interest in activities once enjoyed. It is not a sign of weakness or something you can "just snap out of."

## How it Affects the Brain

Depression causes changes in brain chemistry — particularly in serotonin, dopamine, and norepinephrine. These are neurotransmitters (chemical messengers) that regulate mood, sleep, and motivation.

## Common Symptoms
- Persistent sadness, emptiness, or hopelessness
- Loss of interest in hobbies and activities
- Changes in appetite — eating too much or too little
- Sleep problems (insomnia or sleeping too much)
- Fatigue and low energy
- Difficulty concentrating or making decisions
- Feelings of worthlessness or excessive guilt
- In severe cases: thoughts of death or suicide

## Depression in the Kenyan Context

In Kenya, mental health stigma means many people suffer silently. Common myths include:
- "Depression is a disease of the rich" — FALSE. It affects all income levels.
- "Pray and it will go away" — Prayer helps, but depression also needs medical attention.
- "Just cheer up" — Depression is a medical condition, not a mood choice.

## Recovery is Possible

With the right support — therapy, sometimes medication, lifestyle changes — 80% of people with depression improve significantly. You are not alone, and help is available.''',
        'content_sw': '''## Unyogovu ni Nini?

Unyogovu ni hali ya kawaida na nzito ya kiafya ambayo inaathiri vibaya jinsi unavyohisi, kufikiria, na kutenda. Husababisha hisia za huzuni na kupoteza hamu katika shughuli ulizowahi kufurahia.

## Dalili za Kawaida
- Huzuni inayoendelea, au hali ya kutokuwa na tumaini
- Kupoteza hamu katika mambo uliyopenda
- Mabadiliko ya hamu ya kula
- Matatizo ya kulala
- Uchovu na nguvu kidogo
- Ugumu wa kuzingatia au kufanya maamuzi

## Kupona Kunawezekana

Kwa msaada sahihi — tiba, wakati mwingine dawa, na mabadiliko ya mtindo wa maisha — asilimia 80 ya watu wenye unyogovu wanaboreshaika kwa kiasi kikubwa.''',
        'key_takeaways': [
            'Depression is a medical condition, not a personal failure.',
            'It changes brain chemistry, not just your mood.',
            'Recovery is possible — 80% of people improve with treatment.',
            'Seeking help is a sign of strength, not weakness.',
        ],
        'key_takeaways_sw': [
            'Unyogovu ni hali ya kiafya, si kushindwa kwa mtu binafsi.',
            'Kupona kunawezekana — asilimia 80 ya watu wanaboreshaika.',
        ],
        'duration_minutes': 12,
        'is_premium': False,
        'thumbnail_emoji': '🧠',
        'order': 1,
    },
    {
        'title': 'Coping Strategies for Depression',
        'title_sw': 'Mikakati ya Kukabiliana na Unyogovu',
        'slug': 'coping-strategies-depression',
        'category': 'depression',
        'level': 'intermediate',
        'summary': 'Practical, evidence-based strategies to manage depression day to day.',
        'summary_sw': 'Mikakati ya vitendo inayothibitishwa kwa sayansi ya kukabiliana na unyogovu kila siku.',
        'content': '''## Evidence-Based Coping Strategies

### 1. Behavioural Activation
Depression makes you want to withdraw. Do the opposite — schedule small pleasurable activities even when you don't feel like it. Start tiny: a 10-minute walk, cooking a meal, calling one friend.

### 2. Challenging Negative Thoughts (CBT)
Depression distorts thinking. Common patterns:
- **Catastrophising**: "Everything is ruined" → "This is hard, but not everything."
- **Mind reading**: "They think I'm useless" → "I don't know what they think."
- **All-or-nothing**: "I failed, so I'm a failure" → "I failed at this task, not as a person."

### 3. Physical Activity
Exercise releases endorphins and serotonin. Even 20 minutes of walking 3 times a week reduces depression symptoms by 30-40%.

### 4. Sleep Hygiene
- Same sleep/wake time daily
- No screens 1 hour before bed
- Keep bedroom cool and dark
- Avoid alcohol (it worsens depression)

### 5. Social Connection
Isolation worsens depression. Tell one trusted person how you feel. Join a support group. Connect with community.

### 6. Professional Help
These strategies help but are not a replacement for therapy. A therapist can teach personalised CBT, problem-solving therapy, and more.''',
        'content_sw': '''## Mikakati ya Kukabiliana na Unyogovu

### 1. Uanzishaji wa Tabia
Unyogovu unakufanya utake kujiepusha. Fanya kinyume — panga shughuli ndogo ndogo za kupumzisha hata usipohisi kutaka.

### 2. Changamoto za Mawazo Mabaya
Unyogovu unapotosha mawazo. Jifunze kutambua na kubadilisha mawazo hasi.

### 3. Mazoezi ya Mwili
Mazoezi yanaacha endorphini na serotonini. Hata dakika 20 za kutembea mara 3 kwa wiki hupunguza dalili za unyogovu.''',
        'key_takeaways': [
            'Small actions break the depression cycle — start with 10 minutes.',
            'Exercise is as effective as antidepressants for mild-moderate depression.',
            'Challenging negative thoughts (CBT) reduces relapse risk.',
            'Social connection is medicine — isolation makes depression worse.',
        ],
        'key_takeaways_sw': [
            'Hatua ndogo huvunja mzunguko wa unyogovu.',
            'Mazoezi yanafanya kazi kama dawa kwa unyogovu wa wastani.',
        ],
        'duration_minutes': 15,
        'is_premium': False,
        'thumbnail_emoji': '💪',
        'order': 2,
    },
    # ── ANXIETY ──────────────────────────────────────────────────────────
    {
        'title': 'Understanding Anxiety',
        'title_sw': 'Kuelewa Wasiwasi',
        'slug': 'understanding-anxiety',
        'category': 'anxiety',
        'level': 'beginner',
        'summary': 'What anxiety is, why it happens, and how to tell normal worry from an anxiety disorder.',
        'summary_sw': 'Wasiwasi ni nini, kwa nini hutokea, na jinsi ya kutofautisha wasiwasi wa kawaida na ugonjwa wa wasiwasi.',
        'content': '''## What is Anxiety?

Anxiety is your body's natural response to stress — a feeling of fear or worry about what's to come. It becomes a disorder when it's excessive, persistent, and interferes with daily life.

## The Biology of Anxiety

When you perceive a threat, your brain activates the fight-or-flight response:
- Adrenaline released → heart races, breathing quickens
- Blood flows to muscles → physical tension
- Brain focuses on threat → hard to think of anything else

This system protected our ancestors from predators. Today it fires for job interviews, social situations, and health worries.

## Types of Anxiety
- **GAD (Generalised Anxiety)**: Worry about many things, most days
- **Social Anxiety**: Fear of social situations and judgment
- **Panic Disorder**: Sudden intense episodes of fear (panic attacks)
- **Health Anxiety**: Excessive worry about illness

## Common Physical Symptoms
- Racing heart, chest tightness
- Shortness of breath
- Sweating, trembling
- Nausea, stomach upset
- Dizziness, headaches

## When to Seek Help

Seek professional support if anxiety:
- Lasts more than 6 months
- Causes you to avoid things you need to do
- Significantly affects your work, relationships, or sleep''',
        'content_sw': '''## Wasiwasi ni Nini?

Wasiwasi ni jibu la asili la mwili wako kwa msongo wa mawazo — hisia ya hofu au wasiwasi kuhusu kinachokuja.

## Dalili za Kawaida za Kimwili
- Moyo unaopiga kwa kasi, kifua kuziba
- Upungufu wa pumzi
- Kutokwa na jasho, kutetemeka
- Kichefuchefu, tumbo kuumia

## Wakati wa Kutafuta Msaada

Tafuta msaada wa kitaalamu ikiwa wasiwasi:
- Unadumu zaidi ya miezi 6
- Unakusababishia kuepuka mambo unayohitaji kufanya''',
        'key_takeaways': [
            'Anxiety is a normal biological response that becomes a problem when excessive.',
            'Physical symptoms of anxiety (racing heart, sweating) are real, not imagined.',
            'GAD, social anxiety, and panic disorder are distinct conditions.',
            'Professional help is recommended when anxiety significantly disrupts daily life.',
        ],
        'key_takeaways_sw': [
            'Wasiwasi ni jibu la kawaida la kibiolojia ambalo linakuwa tatizo linapokuwa kupita kiasi.',
        ],
        'duration_minutes': 12,
        'is_premium': False,
        'thumbnail_emoji': '😰',
        'order': 1,
    },
    {
        'title': 'Breathing and Grounding Techniques',
        'title_sw': 'Mbinu za Kupumua na Kudhibiti Wasiwasi',
        'slug': 'breathing-grounding-techniques',
        'category': 'anxiety',
        'level': 'beginner',
        'summary': 'Practical breathing exercises and grounding techniques to calm anxiety in the moment.',
        'summary_sw': 'Mazoezi ya vitendo ya kupumua na mbinu za kudhibiti wasiwasi papo hapo.',
        'content': '''## Why Breathing Works

When anxious, breathing becomes shallow and fast — increasing CO2 imbalance and worsening symptoms. Slow, deep breathing activates the parasympathetic nervous system (rest-and-digest) and calms the anxiety response within minutes.

## Technique 1: Box Breathing (4-4-4-4)
Used by Navy SEALs for stress control:
1. **Inhale** slowly for 4 counts
2. **Hold** for 4 counts
3. **Exhale** slowly for 4 counts
4. **Hold** for 4 counts
5. Repeat 4 times

## Technique 2: 4-7-8 Breathing
1. **Inhale** through nose for 4 counts
2. **Hold** for 7 counts
3. **Exhale** through mouth for 8 counts
4. Repeat 4 cycles

## Technique 3: 5-4-3-2-1 Grounding
When overwhelmed, name:
- **5 things you can SEE**
- **4 things you can TOUCH**
- **3 things you can HEAR**
- **2 things you can SMELL**
- **1 thing you can TASTE**

This pulls your mind from anxious thoughts to present reality.

## Technique 4: Progressive Muscle Relaxation
Tense each muscle group for 5 seconds, then release:
1. Feet → Calves → Thighs → Abdomen → Hands → Arms → Shoulders → Face
2. Notice the contrast between tension and release
3. Full body relaxation follows in ~10 minutes

## Practice Regularly
These techniques work best with daily practice — not just during crisis. 5 minutes of box breathing every morning reduces baseline anxiety significantly.''',
        'content_sw': '''## Kwa Nini Kupumua Kunafanya Kazi

Wakati wa wasiwasi, kupumua kunakuwa kwa haraka na kwa kina kidogo. Kupumua polepole, kwa kina huamsha mfumo wa neva wa parasympathetic na hutuliza jibu la wasiwasi ndani ya dakika chache.

## Mbinu 1: Kupumua kwa Sanduku (4-4-4-4)
1. **Vuta pumzi** polepole kwa hesabu 4
2. **Simama** kwa hesabu 4
3. **Toa pumzi** polepole kwa hesabu 4
4. **Simama** kwa hesabu 4

## Mbinu 2: Udhibiti 5-4-3-2-1
Taja:
- **Vitu 5 unazoweza KUONA**
- **Vitu 4 unazoweza KUGUSA**
- **Vitu 3 unazoweza KUSIKIA**
- **Vitu 2 unazoweza KUNUSA**
- **Kitu 1 unachoweza KUONJA**''',
        'key_takeaways': [
            'Box breathing (4-4-4-4) activates the relaxation response within 2 minutes.',
            '5-4-3-2-1 grounding pulls you from anxious thoughts to present reality.',
            'Daily practice reduces baseline anxiety — not just emergency use.',
            'Progressive muscle relaxation reduces physical tension from anxiety.',
        ],
        'key_takeaways_sw': [
            'Kupumua kwa sanduku (4-4-4-4) kunatuliza wasiwasi ndani ya dakika 2.',
            '5-4-3-2-1 inakuvuta kutoka kwa mawazo ya wasiwasi hadi hali ya sasa.',
        ],
        'duration_minutes': 10,
        'is_premium': False,
        'thumbnail_emoji': '🌬️',
        'order': 2,
    },
    # ── ALCOHOL ──────────────────────────────────────────────────────────
    {
        'title': 'Understanding Alcohol Use Disorder',
        'title_sw': 'Kuelewa Utegemezi wa Pombe',
        'slug': 'understanding-alcohol-use-disorder',
        'category': 'alcohol',
        'level': 'beginner',
        'summary': 'What alcohol use disorder is, how dependence develops, and why willpower alone is rarely enough.',
        'summary_sw': 'Utegemezi wa pombe ni nini, jinsi unavyokua, na kwa nini nguvu ya mapenzi peke yake mara chache inatosha.',
        'content': '''## What is Alcohol Use Disorder (AUD)?

AUD is a medical condition characterised by inability to control drinking despite negative consequences. It exists on a spectrum from mild (hazardous use) to severe (dependence).

## How Dependence Develops

Alcohol affects the brain's reward system by releasing dopamine — the "feel good" chemical. Over time:
1. Brain adapts → needs more alcohol for same effect (tolerance)
2. Without alcohol, brain becomes hyperactive → withdrawal symptoms
3. Drinking becomes compulsive — not a choice but a craving

## Signs of Alcohol Dependence
- Drinking more than intended
- Trying to cut down but failing
- Spending a lot of time drinking or recovering
- Craving alcohol
- Failing work, family, or social obligations due to drinking
- Continuing despite knowing it causes problems
- Withdrawal symptoms when stopping: tremors, sweating, anxiety, seizures (severe)

## Alcohol in Kenya

Kenya has one of Africa's highest alcohol consumption rates. Key facts:
- Common drinks: Senator Keg, chang'aa, mnazi, commercial beer
- Alcohol kills 8,500 Kenyans annually (road accidents, liver disease, violence)
- Many people drink to cope with stress, poverty, trauma

## Why "Just Stop" Doesn't Work

Severe alcohol dependence causes physical withdrawal that can be life-threatening. Sudden stopping without medical support can cause seizures. Medical detox is often needed first.

## Recovery is a Process

Recovery is not linear. Relapses are common and are part of the process — not failure. The average person attempts recovery 4-5 times before sustained sobriety.''',
        'content_sw': '''## Utegemezi wa Pombe ni Nini?

Utegemezi wa pombe ni hali ya kiafya inayoonyeshwa na kutoweza kudhibiti kunywa pombe licha ya matokeo mabaya.

## Dalili za Utegemezi
- Kunywa zaidi ya ulivyokusudia
- Kujaribu kupunguza lakini kushindwa
- Kutumia muda mwingi kunywa au kupona
- Kukosa wajibu wa kazi, familia, au kijamii kwa sababu ya kunywa

## Kupona ni Mchakato

Kupona si mstari ulionyooka. Kurudi nyuma ni kawaida na ni sehemu ya mchakato — si kushindwa.''',
        'key_takeaways': [
            'AUD is a medical condition, not a moral failure or lack of willpower.',
            'Dependence physically changes the brain — which is why stopping is so hard.',
            'Sudden stopping of severe dependence can be dangerous — seek medical support.',
            'Relapse is part of recovery for most people, not the end of recovery.',
        ],
        'key_takeaways_sw': [
            'Utegemezi wa pombe ni hali ya kiafya, si kushindwa kwa maadili.',
            'Kurudi nyuma ni sehemu ya kupona kwa watu wengi, si mwisho wa kupona.',
        ],
        'duration_minutes': 14,
        'is_premium': False,
        'thumbnail_emoji': '🍺',
        'order': 1,
    },
    {
        'title': 'The First 30 Days Sober',
        'title_sw': 'Siku 30 za Kwanza za Kuacha Pombe',
        'slug': 'first-30-days-sober',
        'category': 'alcohol',
        'level': 'beginner',
        'summary': 'What to expect in the first month of sobriety — physically and emotionally — and how to survive it.',
        'summary_sw': 'Nini cha kutarajia katika mwezi wa kwanza wa kuacha pombe — kimwili na kihisia — na jinsi ya kuishi nayo.',
        'content': '''## Days 1-3: Physical Withdrawal

The hardest days physically. Depending on severity of use:
- Mild: Headache, sweating, anxiety, poor sleep
- Moderate: Tremors, nausea, elevated heart rate
- Severe: Hallucinations, seizures (MEDICAL EMERGENCY — seek help)

**Key action**: If you have been a heavy daily drinker, do NOT stop cold turkey without medical supervision.

## Days 4-7: The Fog

Physical symptoms reduce but emotional fog sets in:
- Irritability, mood swings
- Intense cravings (especially evenings)
- Poor sleep, vivid dreams
- Anxiety

**What helps**: Drink lots of water. Eat regularly. Sleep. Call your support person when cravings hit.

## Week 2: The Hard Thinking Begins

Brain starts to clear. You may:
- Feel grief for the alcohol (it was your coping mechanism)
- Face emotions you were drinking to avoid
- Question whether sobriety is worth it

**What helps**: Write a list of why you are doing this. Read it every morning.

## Weeks 3-4: The Pink Cloud (and the Danger)

Some people feel euphoric — energy returns, sleep improves, skin clears. This is the "pink cloud." The danger: overconfidence leads to relapse.

**What helps**: Stay connected to support. Don't isolate. Don't celebrate with "just one drink."

## Milestones to Celebrate
- 24 hours sober: Your body has started to heal
- 1 week: Liver is starting to recover
- 2 weeks: Sleep dramatically improves
- 1 month: Significant brain recovery, risk of relapse dropping''',
        'content_sw': '''## Siku 1-3: Kujiondoa Kimwili

Siku ngumu zaidi kimwili. Kulingana na ukali wa matumizi:
- Kali kidogo: Maumivu ya kichwa, kutokwa jasho, wasiwasi
- Kali wastani: Kutetemeka, kichefuchefu

**Hatua muhimu**: Ikiwa umekuwa mtumiaji mzito wa kila siku, USISIMAME bila msaada wa daktari.

## Hatua za Kusherehekea
- Saa 24 bila pombe: Mwili wako umeanza kupona
- Wiki 1: Ini linaanza kupona
- Mwezi 1: Kupona kwa ubongo kwa kiasi kikubwa''',
        'key_takeaways': [
            'Days 1-3 are hardest physically — heavy drinkers need medical supervision to stop safely.',
            'Week 2 brings emotional pain you were drinking to avoid — this is normal.',
            'The "pink cloud" of weeks 3-4 creates overconfidence — stay connected to support.',
            'Celebrate milestones — 24 hours, 1 week, 1 month all matter.',
        ],
        'key_takeaways_sw': [
            'Siku 1-3 ni ngumu zaidi kimwili.',
            'Sherehekea hatua — saa 24, wiki 1, mwezi 1 zote ni muhimu.',
        ],
        'duration_minutes': 13,
        'is_premium': False,
        'thumbnail_emoji': '📅',
        'order': 2,
    },
    # ── GAMBLING ─────────────────────────────────────────────────────────
    {
        'title': 'Understanding Gambling Disorder',
        'title_sw': 'Kuelewa Ugonjwa wa Kamari',
        'slug': 'understanding-gambling-disorder',
        'category': 'gambling',
        'level': 'beginner',
        'summary': 'How gambling addiction develops in Kenya, the M-Pesa betting crisis, and why it is a brain disease.',
        'summary_sw': 'Jinsi uraibu wa kamari unavyokua nchini Kenya, mgawanyiko wa kucheza kamari kwa M-Pesa, na kwa nini ni ugonjwa wa ubongo.',
        'content': '''## Gambling in Kenya: A Growing Crisis

Kenya has over 10 million active gamblers — nearly 20% of the adult population. The mobile betting revolution (SportPesa, Betika, Odibets) made gambling as easy as sending an M-Pesa message.

**The scale of harm:**
- Average annual loss: KES 50,000–500,000 per problem gambler
- Youth aged 18-35 most affected
- Families destroyed: divorce, domestic violence, loss of homes
- Suicide linked to gambling debt is rising

## How Gambling Disorder Develops

Gambling activates the same dopamine pathways as alcohol and drugs. The brain learns:
1. **Near wins** feel almost as good as wins (keeps people playing)
2. **Variable rewards** (unpredictable wins) are MORE addictive than predictable ones
3. Over time: tolerance → need bigger bets for same thrill
4. Chasing losses: desperate betting to recover lost money

## Signs of Gambling Disorder
- Preoccupied with gambling (thinking about it constantly)
- Need to gamble with increasing amounts
- Failed attempts to cut back or stop
- Restless or irritable when trying to stop
- Gambling to escape problems or relieve dysphoria
- Lying to hide gambling
- Jeopardised relationships, job, education
- Relying on others to bail out financial problems from gambling

## The Betting Industry's Tactics

Platforms are designed to maximise engagement:
- Free bets to start addiction
- "Live" betting keeps you engaged for hours
- "Cash out" creates illusion of control
- Push notifications with personalised offers
- Bonuses that require large wagering before withdrawal

## Recovery is Real

With treatment, 75-80% of people with gambling disorder achieve significant improvement. The key is accepting it is a brain disorder requiring professional help.''',
        'content_sw': '''## Kamari nchini Kenya: Mgawanyiko Unaokua

Kenya ina zaidi ya watumiaji wa kamari 10 milioni. Mapinduzi ya kubeti kwa simu (SportPesa, Betika) yalifanya kamari iwe rahisi kama kutuma ujumbe wa M-Pesa.

## Jinsi Ugonjwa wa Kamari Unavyokua

Kamari huamsha njia sawa za dopamine kama pombe na dawa za kulevya.

## Dalili za Ugonjwa wa Kamari
- Kuzingatiwa na kamari
- Haja ya kubeti kwa kiasi kinachoongezeka
- Majaribio yaliyoshindwa ya kupunguza au kuacha''',
        'key_takeaways': [
            'Kenya has 10M+ gamblers — the mobile betting revolution made addiction easy.',
            'Gambling disorder is a brain disease involving dopamine — not a character flaw.',
            'Near-wins and variable rewards are deliberately designed to keep you hooked.',
            '75-80% of people improve significantly with proper treatment.',
        ],
        'key_takeaways_sw': [
            'Ugonjwa wa kamari ni ugonjwa wa ubongo unaohusisha dopamine.',
            'Asilimia 75-80 ya watu wanaboreshaika kwa matibabu sahihi.',
        ],
        'duration_minutes': 15,
        'is_premium': False,
        'thumbnail_emoji': '🎰',
        'order': 1,
    },
    {
        'title': 'Breaking Free from Betting Apps',
        'title_sw': 'Kujiweka Huru kutoka kwa Programu za Kubeti',
        'slug': 'breaking-free-from-betting-apps',
        'category': 'gambling',
        'level': 'intermediate',
        'summary': 'Step-by-step practical guide to self-exclusion, blocking apps, and protecting your M-Pesa from gambling.',
        'summary_sw': 'Mwongozo wa vitendo wa kujitenga, kuzuia programu, na kulinda M-Pesa yako kutoka kwa kamari.',
        'content': '''## Step 1: Self-Exclusion from Betting Platforms

Most Kenyan betting platforms allow self-exclusion. This blocks your account from being used to bet.

**How to self-exclude on major platforms:**
- **SportPesa**: Call 0709 888 000 or email care@sportpesa.com
- **Betika**: App → Profile → Responsible Gaming → Self-Exclusion
- **Odibets**: Contact support via app chat
- **Betway Kenya**: Account → Responsible Gaming → Self-Exclude

Choose 6 months, 1 year, or permanent. Permanent is best for severe addiction.

## Step 2: Block Betting Websites

**On Safaricom data:**
1. Text "BLOCK" to 100 (Safaricom can block specific websites)
2. Use Google Family Link (works on Android) to block betting sites

**Apps to block:**
- Use "Digital Wellbeing" (Android) → App Timers → Set gambling apps to 0 minutes
- Ask a trusted person to change your phone's screen time password

## Step 3: Protect Your M-Pesa

- Remove M-Pesa from all betting platforms
- Set an M-Pesa daily send limit (Safaricom can reduce your limit)
- Ask a trusted family member to be a co-signatory

## Step 4: Remove Triggers

- Delete all betting apps immediately
- Unsubscribe from betting SMS (text STOP to each platform)
- Block betting-related WhatsApp groups
- Tell friends you are stopping — accountability matters

## Step 5: Find a Replacement Activity

Gambling fills time and provides stimulation. Replace it with:
- Exercise (releases same dopamine naturally)
- Learning a new skill
- Community activities, church, mosque
- Peer support group

## If You Slip

One bet is not failure. Call your support person immediately. Restart your streak. Learn what triggered the slip and plan for next time.''',
        'content_sw': '''## Hatua ya 1: Kujitenga kutoka Majukwaa ya Kubeti

Majukwaa mengi ya kubeti Kenya yanaruhusu kujitenga.

**Jinsi ya kujitenga kwenye majukwaa makubwa:**
- **SportPesa**: Piga simu 0709 888 000
- **Betika**: Programu → Wasifu → Mchezo Unaowajibika → Kujitenga

## Hatua ya 3: Linda M-Pesa Yako

- Ondoa M-Pesa kutoka kwa majukwaa yote ya kubeti
- Weka kikomo cha kutuma kwa siku cha M-Pesa
- Mwambie mtu wa familia anayeaminika awe msimamizi''',
        'key_takeaways': [
            'Self-exclusion is available on all major Kenyan betting platforms — use it.',
            'Protecting M-Pesa (daily limits, remove from betting accounts) is critical.',
            'One slip is not failure — restart your streak and learn from the trigger.',
            'Exercise and community replace the dopamine gambling provided.',
        ],
        'key_takeaways_sw': [
            'Kujitenga kunapatikana kwenye majukwaa yote ya kubeti Kenya.',
            'Kulinda M-Pesa ni muhimu.',
        ],
        'duration_minutes': 14,
        'is_premium': True,
        'thumbnail_emoji': '📵',
        'order': 2,
    },
    # ── TOBACCO ──────────────────────────────────────────────────────────
    {
        'title': 'Quitting Tobacco: Your First Week',
        'title_sw': 'Kuacha Tumbaku: Wiki Yako ya Kwanza',
        'slug': 'quitting-tobacco-first-week',
        'category': 'tobacco',
        'level': 'beginner',
        'summary': 'A day-by-day guide to surviving the hardest week of quitting tobacco or cigarettes.',
        'summary_sw': 'Mwongozo wa siku hadi siku wa kuishi wiki ngumu zaidi ya kuacha tumbaku.',
        'content': '''## Why the First Week is Hardest

Nicotine leaves your system within 72 hours. After that, cravings are psychological — your brain has been trained to expect nicotine. The first week is when 80% of quit attempts fail.

## Day 1: Set Your Quit Date and Prepare
- Tell 3 people you are quitting (accountability)
- Remove all cigarettes, lighters, ashtrays from home
- Download a quit tracker app (or use the sobriety tracker here)
- Buy sugar-free gum, carrots, sunflower seeds (hand-to-mouth replacements)
- Plan for your 3 biggest trigger moments (after meals, with coffee, stress)

## Days 2-3: Peak Withdrawal
Nicotine leaves your body. Expect:
- Intense cravings (last 3-5 minutes each — ride them out)
- Irritability, difficulty concentrating
- Headache, increased appetite
- Cough (lungs clearing — good sign)

**Survive each craving**: 5-4-3-2-1 grounding. Deep breathing. Call someone.

## Days 4-5: The Anger Days
Withdrawal peaks emotionally. You may feel:
- Rage, frustration, low mood
- This is NORMAL — your brain adjusting

**What helps**: Exercise. Drink water. Remember: cravings peak and pass.

## Days 6-7: The First Victory
Physical cravings significantly reduced. Sleep may still be disrupted.
- Celebrate 1 week — you have done something remarkable
- Risk of relapse still high — avoid alcohol and triggering social situations

## Cravings Management: The 4 D's
1. **Delay**: Wait 5 minutes. The craving will reduce.
2. **Deep breathe**: 3 slow deep breaths
3. **Drink water**: Glass of cold water
4. **Do something else**: Walk, call someone, chew gum''',
        'content_sw': '''## Kwa Nini Wiki ya Kwanza ni Ngumu Zaidi

Nikotini inatoka mwilini ndani ya masaa 72. Baada ya hapo, tamaa ni za kisaikolojia tu.

## Siku ya 1: Weka Tarehe ya Kuacha na Jiandae
- Mwambie watu 3 unaowacha
- Ondoa sigara zote, viberiti, na sufuria za majivu kutoka nyumbani

## Usimamizi wa Tamaa: D 4
1. **Chelewa**: Subiri dakika 5. Tamaa itapungua.
2. **Pumua kwa kina**: Pumzi 3 za polepole
3. **Kunywa maji**: Glasi ya maji baridi
4. **Fanya kitu kingine**: Tembea, piga simu mtu''',
        'key_takeaways': [
            'Nicotine leaves your body in 72 hours — after that, cravings are psychological.',
            'Each craving lasts 3-5 minutes — ride it out with the 4 D\'s.',
            'Days 4-5 are emotionally hardest — irritability is normal withdrawal.',
            '1 week smoke-free is a major achievement — celebrate it.',
        ],
        'key_takeaways_sw': [
            'Nikotini inatoka mwilini ndani ya masaa 72.',
            'Kila tamaa hudumu dakika 3-5 — isimame kwa D 4.',
        ],
        'duration_minutes': 12,
        'is_premium': False,
        'thumbnail_emoji': '🚬',
        'order': 1,
    },
    # ── WELLNESS ─────────────────────────────────────────────────────────
    {
        'title': 'Sleep and Mental Health',
        'title_sw': 'Usingizi na Afya ya Akili',
        'slug': 'sleep-and-mental-health',
        'category': 'wellness',
        'level': 'beginner',
        'summary': 'Why sleep is as important as diet and exercise for mental health, and how to fix your sleep.',
        'summary_sw': 'Kwa nini usingizi ni muhimu kama lishe na mazoezi kwa afya ya akili, na jinsi ya kurekebisha usingizi wako.',
        'content': '''## Sleep and Mental Health Are Deeply Connected

Sleep deprivation causes: anxiety, depression, poor decision-making, irritability, and weakened immune system. People with depression are 3× more likely to have insomnia. It's a two-way relationship — poor sleep worsens mental health, and poor mental health disrupts sleep.

## How Much Sleep Do You Need?
- Adults: 7-9 hours
- Teenagers: 8-10 hours
- Under 12: 9-12 hours

Most Kenyans in cities get 5-6 hours — significantly below recommended.

## Signs of Sleep Deprivation
- Difficulty waking up or excessive daytime sleepiness
- Irritability and mood swings
- Poor concentration and memory
- Craving sugary/high-carb foods
- Weakened immune system (frequent illness)

## 8 Evidence-Based Sleep Tips

1. **Same time every day**: Wake at the same time (even weekends). This anchors your circadian rhythm.
2. **No screens 60 minutes before bed**: Blue light suppresses melatonin (your sleep hormone).
3. **Keep bedroom cool**: 18-21°C is ideal for sleep.
4. **No caffeine after 2 PM**: Caffeine has a 6-hour half-life.
5. **Exercise, but not late**: Exercise improves sleep — but not within 3 hours of bedtime.
6. **Reserve bed for sleep**: Don't work or watch TV in bed.
7. **Manage worry**: Write tomorrow's to-do list before bed — "offloads" worry from your brain.
8. **No alcohol**: Alcohol disrupts REM sleep — you feel tired even after 8 hours.

## If You Cannot Sleep: The 20-Minute Rule

If you can't sleep after 20 minutes, get up. Do something calm (read, stretch) until sleepy, then return to bed. This prevents associating bed with wakefulness.''',
        'content_sw': '''## Usingizi na Afya ya Akili Vinaunganishwa Kwa Kina

Ukosefu wa usingizi husababisha: wasiwasi, unyogovu, maamuzi mabaya, na hasira.

## Unahitaji Kulala Kiasi Gani?
- Watu wazima: Masaa 7-9
- Vijana: Masaa 8-10

## Vidokezo 8 vya Usingizi

1. **Wakati sawa kila siku**: Amka wakati sawa kila siku.
2. **Hakuna skrini dakika 60 kabla ya kulala**: Mwanga wa bluu huzuia melatonin.
3. **Weka chumba baridi**: 18-21°C ni bora kwa usingizi.
4. **Hakuna kafeini baada ya saa 8 alasiri**.''',
        'key_takeaways': [
            'Sleep deprivation causes anxiety, depression, and poor decision-making.',
            'Consistent wake time is the single most important sleep hygiene habit.',
            'No screens 60 minutes before bed — blue light suppresses sleep hormones.',
            'Alcohol disrupts REM sleep — you feel tired even after 8 hours.',
        ],
        'key_takeaways_sw': [
            'Ukosefu wa usingizi husababisha wasiwasi na unyogovu.',
            'Wakati sawa wa kuamka ndio tabia muhimu zaidi ya usafi wa usingizi.',
        ],
        'duration_minutes': 11,
        'is_premium': False,
        'thumbnail_emoji': '😴',
        'order': 1,
    },
    {
        'title': 'Managing Stress at Work',
        'title_sw': 'Kudhibiti Msongo wa Mawazo Kazini',
        'slug': 'managing-work-stress',
        'category': 'wellness',
        'level': 'intermediate',
        'summary': 'Practical strategies for managing workplace stress in the Kenyan professional context.',
        'summary_sw': 'Mikakati ya vitendo ya kudhibiti msongo wa mawazo kazini katika muktadha wa wataalamu wa Kenya.',
        'content': '''## Workplace Stress in Kenya

Kenyan professionals face unique stressors: job insecurity, long commutes (Nairobi traffic averages 2-3 hours daily), demanding bosses, financial pressure, and rapidly changing work environments.

## Types of Work Stress
- **Overload**: Too much to do, too little time
- **Underload**: Boring, meaningless work (just as stressful)
- **Role conflict**: Conflicting demands from different people
- **Lack of control**: No say in decisions that affect you
- **Poor relationships**: Difficult colleagues or bosses
- **Insecurity**: Fear of losing your job

## The Burnout Spectrum

Stress → Burnout → Breakdown

**Burnout signs:**
- Emotional exhaustion — dreading going to work
- Cynicism — nothing matters, what's the point?
- Reduced effectiveness — doing the minimum
- Physical symptoms: headaches, insomnia, frequent illness

## Evidence-Based Stress Management

**At work:**
- **Time-box your day**: 90-minute focused work blocks → 10-minute break
- **Learn to say no**: "I have too much on my plate to do this well right now"
- **Communicate proactively**: Most stress comes from uncertainty — update your manager often
- **Take all your leave**: Annual leave exists for recovery

**After work:**
- **Transition ritual**: Change clothes, go for a walk — tells brain "work is done"
- **Protect evenings**: No work email after 7 PM
- **Weekly review**: 30 minutes Sunday to plan the week ahead (reduces Sunday anxiety)

**Mind:**
- **Mindfulness**: 10 minutes daily (apps: Calm, Headspace)
- **Exercise**: Best stress reliever known to science
- **Social support**: Talk to someone you trust

## When to Seek Help

Seek professional help if stress:
- Has lasted more than 3 months
- Is affecting your relationships or physical health
- Is leading you to use alcohol or substances to cope
- Includes thoughts of self-harm''',
        'content_sw': '''## Msongo wa Mawazo Kazini nchini Kenya

Wataalamu wa Kenya wanakabiliwa na msongo wa kipekee: kutokuwa na uhakika wa kazi, safari ndefu za kwenda kazini, wasimamizi wa kudai.

## Ushahidi wa Kudhibiti Msongo wa Mawazo

**Kazini:**
- **Zungusha wakati wako**: Vitalu vya kufanya kazi kwa makini vya dakika 90 → mapumziko ya dakika 10
- **Jifunze kusema hapana**: "Nina mengi sana ya kufanya kwa sasa"

**Baada ya kazi:**
- **Ibada ya mpito**: Badilisha nguo, nenda kutembea
- **Linda majira ya jioni**: Hakuna barua pepe ya kazi baada ya saa 1 usiku''',
        'key_takeaways': [
            'Burnout follows stress if not managed — watch for exhaustion, cynicism, reduced effectiveness.',
            '90-minute time-boxing is more effective than multitasking all day.',
            'Transition rituals (changing clothes, walking) help your brain leave work behind.',
            'Exercise is the most effective stress reliever known to science.',
        ],
        'key_takeaways_sw': [
            'Kuchoka kunafuata msongo ikiwa hakujadhibitiwa.',
            'Mazoezi ndiyo dawa bora ya msongo inayojulikana na sayansi.',
        ],
        'duration_minutes': 14,
        'is_premium': True,
        'thumbnail_emoji': '💼',
        'order': 2,
    },
    # ── RELATIONSHIPS ─────────────────────────────────────────────────────
    {
        'title': 'Supporting a Loved One in Recovery',
        'title_sw': 'Kusaidia Mpendwa Wako katika Kupona',
        'slug': 'supporting-loved-one-in-recovery',
        'category': 'relationships',
        'level': 'beginner',
        'summary': 'How to support someone recovering from addiction without enabling them or burning yourself out.',
        'summary_sw': 'Jinsi ya kusaidia mtu anayepona kutoka kwa uraibu bila kuwaruhusu au kujichoshea.',
        'content': '''## The Family's Role in Recovery

Recovery doesn't happen in isolation. Family support significantly improves outcomes — but the wrong kind of support can sabotage recovery.

## What Helps

**Do:**
- Learn about addiction (it IS a brain disease — not a choice)
- Set clear, loving boundaries: "I love you, and I will not give you money for alcohol"
- Celebrate milestones — 7 days, 30 days, 90 days matter
- Be consistent — mixed messages confuse and enable
- Take care of yourself — you cannot pour from an empty cup
- Attend family therapy or Al-Anon meetings
- Ask "How can I support you today?" — let them tell you what helps

**Don't:**
- Make excuses or cover up for their behaviour (enabling)
- Give money that may be used for substances
- Threaten and not follow through (empty ultimatums)
- Make recovery all about you and your feelings
- Expect linear progress — relapse is part of recovery for most people
- Ignore your own needs

## Enabling vs Supporting

| Enabling | Supporting |
|----------|-----------|
| Calling work to cover sick days caused by drinking | Encouraging them to call work themselves |
| Giving money "for food" knowing it goes to gambling | Paying a bill directly rather than giving cash |
| Making excuses for their behaviour to others | Being honest: "He is dealing with addiction" |
| Fixing their problems to avoid conflict | Letting them face natural consequences |

## Take Care of Yourself

Living with someone in recovery is exhausting. Common effects on families:
- Anxiety, depression, secondary trauma
- Financial stress
- Social isolation (shame)
- Anger and resentment

**Resources for families in Kenya:**
- Al-Anon Kenya: Support for families of alcoholics
- NACADA: 1192 (counselling for families)
- Family therapy through this platform (book a session)''',
        'content_sw': '''## Jukumu la Familia katika Kupona

Msaada wa familia unaboresha matokeo kwa kiasi kikubwa — lakini aina mbaya ya msaada inaweza kukurupusha kupona.

## Nini Kinachosaidia

**Fanya:**
- Jifunza kuhusu uraibu
- Weka mipaka wazi, yenye upendo
- Sherehekea hatua
- Jitunze mwenyewe

**Usifanye:**
- Toa udhuru au ficha tabia yao (uimarishaji)
- Toa pesa inayoweza kutumika kwa vitu
- Kutishia na kutofuata''',
        'key_takeaways': [
            'Family support improves recovery outcomes — but the wrong kind enables addiction.',
            'Enabling (covering up, giving cash, making excuses) worsens addiction long-term.',
            'Setting loving boundaries ("I love you AND will not give money for drinks") is not cruel.',
            'Family members of people with addiction need their own support — Al-Anon, therapy.',
        ],
        'key_takeaways_sw': [
            'Msaada wa familia unaboresha matokeo ya kupona.',
            'Uimarishaji unazidisha uraibu kwa muda mrefu.',
        ],
        'duration_minutes': 13,
        'is_premium': False,
        'thumbnail_emoji': '❤️',
        'order': 1,
    },
]

created = 0
for l in lessons:
    _, made = Lesson.objects.get_or_create(slug=l['slug'], defaults=l)
    if made:
        created += 1
    print(f"  {'✓' if made else '·'} {l['title'][:55]} ({'new' if made else 'exists'})")

print(f"\nSeeded {created} new lessons ({len(lessons)} total).")
