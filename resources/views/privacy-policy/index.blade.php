@extends('layouts.app')

@php
    SEOMeta::setTitle('Privacy policy');

    $sections = [
        'information-we-collect' => 'What information do we collect?',
        'how-we-use-information' => 'How do we use your information?',
        'sharing-information' => 'Will your information be shared with anyone?',
        'third-parties' => 'Who will your information be shared with?',
        'cookies' => 'Do we use cookies and other tracking technologies?',
        'retention' => 'How long do we keep your information?',
        'security' => 'How do we keep your information safe?',
        'privacy-rights' => 'What are your privacy rights?',
        'data-breach' => 'Data breach',
        'do-not-track' => 'Controls for Do-Not-Track features',
        'california' => 'Do California residents have specific privacy rights?',
        'updates' => 'Do we make updates to this policy?',
        'contact' => 'How can you contact us about this policy?',
    ];

    $sectionNumbers = array_flip(array_keys($sections));
@endphp

@section('content')
    <div class="loadout-page legal">
        <header class="legal-header">
            <h1 class="legal-header__title">Privacy policy</h1>
            <p class="legal-header__meta">Last updated <time datetime="2026-11-19">November 19, 2026</time></p>
        </header>

        <div class="legal-layout">
            <nav class="legal-toc" aria-labelledby="legal-toc-heading">
                <h2 id="legal-toc-heading" class="loadout-eyebrow">Table of contents</h2>
                <ol>
                    @foreach ($sections as $id => $title)
                        <li><a href="#{{ $id }}">{{ $title }}</a></li>
                    @endforeach
                </ol>
            </nav>

            <article class="legal-document prose prose-invert blog-prose">
                <p>
                    Thank you for choosing to be part of our community at Karl.gg (“Company”, “we”, “us”, or “our”). We
                    are committed to protecting your personal information and your right to privacy. If you have any
                    questions or concerns about our policy, or our practices with regards to your personal information,
                    please contact us at <a href="mailto:admin@karl.gg">admin@karl.gg</a>.
                </p>
                <p>
                    When you visit our website <a href="https://karl.gg">https://karl.gg</a>, and use our services, you
                    trust us with your personal information. We take your privacy very seriously. In this privacy policy,
                    we seek to explain to you in the clearest way possible what information we collect, how we use it and
                    what rights you have in relation to it. We hope you take some time to read through it carefully, as it
                    is important. If there are any terms in this privacy policy that you do not agree with, please
                    discontinue use of our Sites and our services.
                </p>
                <p>
                    This privacy policy applies to all information collected through our website (such as
                    https://karl.gg), and/or any related services, sales, marketing or events (we refer to them
                    collectively in this privacy policy as the "Services").
                </p>
                <p>
                    Please read this privacy policy carefully as it will help you make informed decisions about sharing
                    your personal information with us.
                </p>

                <section id="information-we-collect" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['information-we-collect'] + 1 }}.</span> {{ $sections['information-we-collect'] }}</h2>

                    <h3>Personal information you disclose to us</h3>
                    <p class="legal-summary"><strong>In Short:</strong> We collect personal information that you provide to us.</p>
                    <p>
                        We collect personal information that you voluntarily provide to us when registering at the
                        Services, when participating in activities on the Services (such as publishing builds, posting
                        comments, voting on or favoriting builds) or otherwise contacting us.
                    </p>
                    <p>
                        The personal information that we collect depends on the context of your interactions with us and
                        the Services, the choices you make and the products and features you use. The personal
                        information we collect can include the following:
                    </p>
                    <ul>
                        <li>
                            <strong>Account Information.</strong> We collect the display name, email address and password
                            you provide when you register. Passwords are stored in hashed form.
                        </li>
                        <li>
                            <strong>Content You Publish.</strong> We collect the builds, comments, votes and favorites you
                            create on the Services. Builds and comments are publicly visible together with your display
                            name.
                        </li>
                        <li>
                            <strong>Guest Comments.</strong> If you comment without an account, we collect the name, email
                            address and optional website you enter. Your name and website are shown with your comment; your
                            email address is not.
                        </li>
                    </ul>
                    <p>
                        All personal information that you provide to us must be true, complete and accurate, and you must
                        notify us of any changes to such personal information.
                    </p>

                    <h3>Information automatically collected</h3>
                    <p class="legal-summary">
                        <strong>In Short:</strong> Some information — such as IP address and/or browser and device
                        characteristics — is collected automatically when you visit our Services.
                    </p>
                    <p>
                        We automatically collect certain information when you visit, use or navigate the Services. This
                        information does not reveal your specific identity (like your name or contact information) but
                        may include device and usage information, such as your IP address, browser and device
                        characteristics, operating system, language preferences, referring URLs, device name, country,
                        location, information about how and when you use our Services and other technical information.
                        This information is primarily needed to maintain the security and operation of our Services, and
                        for our internal analytics and reporting purposes.
                    </p>
                    <p>
                        <strong>Online Identifiers.</strong> We collect tools and protocols, such as IP (Internet
                        Protocol) addresses; and other similar data. We store your IP address and browser user agent with
                        your login session and with any comment you post.
                    </p>
                </section>

                <section id="how-we-use-information" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['how-we-use-information'] + 1 }}.</span> {{ $sections['how-we-use-information'] }}</h2>

                    <p class="legal-summary">
                        <strong>In Short:</strong> We process your information for purposes based on legitimate business
                        interests, the fulfillment of our contract with you, compliance with our legal obligations,
                        and/or your consent.
                    </p>
                    <p>
                        We use personal information collected via our Services for a variety of business purposes
                        described below. We process your personal information for these purposes in reliance on our
                        legitimate business interests, in order to enter into or perform a contract with you, with your
                        consent, and/or for compliance with our legal obligations. We indicate the specific processing
                        grounds we rely on next to each purpose listed below.
                    </p>
                    <p>We use the information we collect or receive:</p>
                    <ul>
                        <li>
                            <strong>To facilitate account creation and logon process.</strong> We use your account
                            information to create your account and let you sign in, for the performance of the contract.
                        </li>
                        <li>
                            <strong>To publish your content.</strong> We display the builds and comments you publish,
                            together with your display name and a profile image from Gravatar, for the performance of the
                            contract.
                        </li>
                        <li>
                            <strong>To send you service emails.</strong> We use your email address to send password
                            resets and, when a new game patch is released, notices that your builds may be out of date,
                            based on our legitimate business interests.
                        </li>
                        <li>
                            <strong>To keep our Services secure and working.</strong> We use device and usage information
                            to protect against abuse, troubleshoot problems and moderate comments, based on our legitimate
                            business interests.
                        </li>
                        <li>
                            <strong>For analytics and advertising.</strong> We use Google Analytics to understand how the
                            Services are used, and we display advertising through Google AdSense, which helps fund the
                            Services. See "Do we use cookies and other tracking technologies?" below.
                        </li>
                    </ul>
                </section>

                <section id="sharing-information" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['sharing-information'] + 1 }}.</span> {{ $sections['sharing-information'] }}</h2>

                    <p class="legal-summary">
                        <strong>In Short:</strong> We only share information with your consent, to comply with laws, to
                        provide you with services, to protect your rights, or to fulfill business obligations.
                    </p>
                    <p>We may process or share data based on the following legal basis:</p>
                    <ul>
                        <li>
                            <strong>Consent:</strong> We may process your data if you have given us specific consent to
                            use your personal information in a specific purpose.
                        </li>
                        <li>
                            <strong>Legitimate Interests:</strong> We may process your data when it is reasonably
                            necessary to achieve our legitimate business interests.
                        </li>
                        <li>
                            <strong>Performance of a Contract:</strong> Where we have entered into a contract with you, we
                            may process your personal information to fulfill the terms of our contract.
                        </li>
                        <li>
                            <strong>Legal Obligations:</strong> We may disclose your information where we are legally
                            required to do so in order to comply with applicable law, governmental requests, a judicial
                            proceeding, court order, or legal process, such as in response to a court order or a subpoena
                            (including in response to public authorities to meet national security or law enforcement
                            requirements).
                        </li>
                        <li>
                            <strong>Vital Interests:</strong> We may disclose your information where we believe it is
                            necessary to investigate, prevent, or take action regarding potential violations of our
                            policies, suspected fraud, situations involving potential threats to the safety of any person
                            and illegal activities, or as evidence in litigation in which we are involved.
                        </li>
                    </ul>
                    <p>
                        More specifically, we may need to process your data or share your personal information in the
                        following situations:
                    </p>
                    <ul>
                        <li>
                            <strong>Vendors, Consultants and Other Third-Party Service Providers.</strong> We may share
                            your data with third party vendors, service providers, contractors or agents who perform
                            services for us or on our behalf and require access to such information to do that work.
                            Examples include: data analysis, email delivery, hosting services, backups and
                            advertising. We may allow selected third parties to use tracking
                            technology on the Services, which will enable them to collect data about how you interact
                            with the Services over time. This information may be used to, among other things, analyze and
                            track data, determine the popularity of certain content and better understand online
                            activity. Unless described in this Policy, we do not share, sell, rent or trade any of your
                            information with third parties for their promotional purposes. We have contracts in place
                            with our data processors. This means that they cannot do anything with your personal
                            information unless we have instructed them to do it. They will not share your personal
                            information with any organisation apart from us. They will hold it securely and retain it for
                            the period we instruct.
                        </li>
                        <li>
                            <strong>Business Transfers.</strong> We may share or transfer your information in connection
                            with, or during negotiations of, any merger, sale of company assets, financing, or acquisition
                            of all or a portion of our business to another company.
                        </li>
                    </ul>
                </section>

                <section id="third-parties" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['third-parties'] + 1 }}.</span> {{ $sections['third-parties'] }}</h2>

                    <p class="legal-summary"><strong>In Short:</strong> We only share information with the following third parties.</p>
                    <p>
                        We only share and disclose your information with the following third parties. We have categorized
                        each party so that you may be easily understand the purpose of our data collection and
                        processing practices. If we have processed your data based on your consent and you wish to revoke
                        your consent, please contact us.
                    </p>
                    <dl class="legal-parties">
                        <div>
                            <dt>Cloud Computing Services</dt>
                            <dd>Amazon Web Services (AWS)</dd>
                        </div>
                        <div>
                            <dt>Web and Mobile Analytics</dt>
                            <dd>Google Analytics</dd>
                        </div>
                        <div>
                            <dt>Advertising</dt>
                            <dd>Google AdSense</dd>
                        </div>
                        <div>
                            <dt>Profile Images</dt>
                            <dd>Gravatar</dd>
                        </div>
                        <div>
                            <dt>Email Delivery</dt>
                            <dd>A transactional email provider</dd>
                        </div>
                    </dl>
                    <p>
                        To display profile images, a hashed form of your email address is sent to Gravatar. If you have
                        no Gravatar account, a default image is shown.
                    </p>
                </section>

                <section id="cookies" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['cookies'] + 1 }}.</span> {{ $sections['cookies'] }}</h2>

                    <p class="legal-summary">
                        <strong>In Short:</strong> We use cookies to keep you signed in, and our analytics and
                        advertising partners use cookies to measure usage and serve ads.
                    </p>
                    <p>We and our partners use cookies and similar technologies for the following purposes:</p>
                    <ul>
                        <li>
                            <strong>Essential cookies.</strong> We set cookies to keep you signed in and to protect forms
                            against cross-site request forgery. The Services do not work correctly without them.
                        </li>
                        <li>
                            <strong>Analytics cookies.</strong> Google Analytics sets cookies to collect information about
                            how visitors use the Services, such as pages visited and time spent on them.
                        </li>
                        <li>
                            <strong>Advertising cookies.</strong> Google AdSense and its partners use cookies to serve ads
                            based on your prior visits to this and other websites. You can opt out of personalized
                            advertising at <a href="https://adssettings.google.com" rel="noopener">Google Ads Settings</a>.
                            Google describes how it uses data from sites that use its services at
                            <a href="https://policies.google.com/technologies/partner-sites" rel="noopener">policies.google.com/technologies/partner-sites</a>.
                        </li>
                    </ul>
                    <p>
                        Most web browsers let you block or delete cookies through their settings. If you block essential
                        cookies, you will not be able to sign in.
                    </p>
                </section>

                <section id="retention" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['retention'] + 1 }}.</span> {{ $sections['retention'] }}</h2>

                    <p class="legal-summary">
                        <strong>In Short:</strong> We keep your information for as long as necessary to fulfill the
                        purposes outlined in this privacy policy unless otherwise required by law.
                    </p>
                    <p>
                        We will only keep your personal information for as long as it is necessary for the purposes set
                        out in this privacy policy, unless a longer retention period is required or permitted by law (such
                        as tax, accounting or other legal requirements). No purpose in this policy will require us keeping
                        your personal information for longer than the period of time in which users have an account with
                        us.
                    </p>
                    <p>
                        When we have no ongoing legitimate business need to process your personal information, we will
                        either delete or anonymize it, or, if this is not possible (for example, because your personal
                        information has been stored in backup archives), then we will securely store your personal
                        information and isolate it from any further processing until deletion is possible.
                    </p>
                </section>

                <section id="security" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['security'] + 1 }}.</span> {{ $sections['security'] }}</h2>

                    <p class="legal-summary">
                        <strong>In Short:</strong> We aim to protect your personal information through a system of
                        organizational and technical security measures.
                    </p>
                    <p>
                        We have implemented appropriate technical and organizational security measures designed to
                        protect the security of any personal information we process. However, please also remember that
                        we cannot guarantee that the internet itself is 100% secure. Although we will do our best to
                        protect your personal information, transmission of personal information to and from our Services
                        is at your own risk. You should only access the services within a secure environment.
                    </p>
                </section>

                <section id="privacy-rights" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['privacy-rights'] + 1 }}.</span> {{ $sections['privacy-rights'] }}</h2>

                    <p class="legal-summary"><strong>In Short:</strong> You may review, change, or terminate your account at any time.</p>
                    <p>
                        If you are resident in the European Economic Area and you believe we are unlawfully processing
                        your personal information, you also have the right to complain to your local data protection
                        supervisory authority. You can find their contact details here:
                        <a href="http://ec.europa.eu/justice/data-protection/bodies/authorities/index_en.htm" rel="noopener">http://ec.europa.eu/justice/data-protection/bodies/authorities/index_en.htm</a>.
                    </p>
                    <p>
                        If you have questions or comments about your privacy rights, you may email us at
                        <a href="mailto:admin@karl.gg">admin@karl.gg</a>.
                    </p>

                    <h3>Account Information</h3>
                    <p>
                        If you would at any time like to review or change the information in your account or terminate
                        your account, you can:
                    </p>
                    <ul>
                        <li>Update your display name, email address or password in your account settings.</li>
                        <li>Delete your account from your account settings.</li>
                        <li>Contact us using the contact information provided.</li>
                    </ul>
                    <p>
                        When you delete your account, we permanently delete your account information, favorites, votes and
                        login sessions from our active databases. Builds and comments you have published remain on the
                        Services but are no longer associated with you and are shown as "Anonymous". Copies of deleted
                        information may remain in our backup archives until those backups expire.
                    </p>
                    <p>
                        <strong>Opting out of email marketing:</strong> You can unsubscribe from our marketing email list
                        at any time by clicking on the unsubscribe link in the emails that we send or by contacting us
                        using the details provided below. You will then be removed from the marketing email list –
                        however, we will still need to send you service-related emails that are necessary for the
                        administration and use of your account.
                    </p>
                </section>

                <section id="data-breach" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['data-breach'] + 1 }}.</span> {{ $sections['data-breach'] }}</h2>

                    <p>
                        A privacy breach occurs when there is unauthorized access to or collection, use, disclosure or
                        disposal of personal information. You will be notified about data breaches when Karl.gg believes
                        you are likely to be at risk or serious harm. For example, a data breach may be likely to result
                        in serious financial harm or harm to your mental or physical well-being. In the event that Karl.gg
                        becomes aware of a security breach which has resulted or may result in unauthorized access, use or
                        disclosure of personal information Karl.gg will promptly investigate the matter and notify the
                        applicable Supervisory Authority not later than 72 hours after having become aware of it, unless
                        the personal data breach is unlikely to result in a risk to the rights and freedoms of natural
                        persons.
                    </p>
                </section>

                <section id="do-not-track" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['do-not-track'] + 1 }}.</span> {{ $sections['do-not-track'] }}</h2>

                    <p>
                        Most web browsers and some mobile operating systems and mobile applications include a
                        Do-Not-Track (“DNT”) feature or setting you can activate to signal your privacy preference not to
                        have data about your online browsing activities monitored and collected. No uniform technology
                        standard for recognizing and implementing DNT signals has been finalized. As such, we do not
                        currently respond to DNT browser signals or any other mechanism that automatically communicates
                        your choice not to be tracked online. If a standard for online tracking is adopted that we must
                        follow in the future, we will inform you about that practice in a revised version of this privacy
                        policy.
                    </p>
                </section>

                <section id="california" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['california'] + 1 }}.</span> {{ $sections['california'] }}</h2>

                    <p class="legal-summary">
                        <strong>In Short:</strong> Yes, if you are a resident of California, you are granted specific
                        rights regarding access to your personal information.
                    </p>
                    <p>
                        California Civil Code Section 1798.83, also known as the “Shine The Light” law, permits our users
                        who are California residents to request and obtain from us, once a year and free of charge,
                        information about categories of personal information (if any) we disclosed to third parties for
                        direct marketing purposes and the names and addresses of all third parties with which we shared
                        personal information in the immediately preceding calendar year. If you are a California resident
                        and would like to make such a request, please submit your request in writing to us using the
                        contact information provided below.
                    </p>
                    <p>
                        If you are under 18 years of age, reside in California, and have a registered account with the
                        Services, you have the right to request removal of unwanted data that you publicly post on the
                        Services. To request removal of such data, please contact us using the contact information
                        provided below, and include the email address associated with your account and a statement that
                        you reside in California. We will make sure the data is not publicly displayed on the Services,
                        but please be aware that the data may not be completely or comprehensively removed from our
                        systems.
                    </p>
                </section>

                <section id="updates" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['updates'] + 1 }}.</span> {{ $sections['updates'] }}</h2>

                    <p class="legal-summary">
                        <strong>In Short:</strong> Yes, we will update this policy as necessary to stay compliant with
                        relevant laws.
                    </p>
                    <p>
                        We may update this privacy policy from time to time. The updated version will be indicated by an
                        updated “Revised” date and the updated version will be effective as soon as it is accessible. If
                        we make material changes to this privacy policy, we may notify you either by prominently posting a
                        notice of such changes or by directly sending you a notification. We encourage you to review this
                        privacy policy frequently to be informed of how we are protecting your information.
                    </p>
                </section>

                <section id="contact" class="legal-section">
                    <h2><span class="legal-section__number">{{ $sectionNumbers['contact'] + 1 }}.</span> {{ $sections['contact'] }}</h2>

                    <p>
                        If you have questions or comments about this policy, you may email us at
                        <a href="mailto:admin@karl.gg">admin@karl.gg</a>.
                    </p>

                    <h3>How can you review, update, or delete the data we collect from you?</h3>
                    <p>
                        Based on the laws of some countries, you may have the right to request access to the personal
                        information we collect from you, change that information, or delete it in some circumstances. You
                        can update or delete your account information from your account settings. To request to review,
                        update, or delete any other personal information, please email us at
                        <a href="mailto:admin@karl.gg">admin@karl.gg</a>. We will respond to your request within 30 days.
                    </p>
                </section>

                <p class="legal-attribution">This privacy policy was created using Termly’s Privacy Policy Generator.</p>
            </article>
        </div>
    </div>
@endsection
