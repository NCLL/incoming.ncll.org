<?php include('template/header.html'); ?>

        <section class="cta-container clearfix" style="background-image: url(/img/example-family.jpg);">
            <section class="main-form">
                <form class="signup" method="post" action="lib/process_form.php">
                    <h2>Sign Up</h2>
                    <fieldset class="contact">
                        <legend class="visuallyhidden"><h3>Contact Information</h3></legend>
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" placeholder="John" required autocomplete="given-name">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Doe" required autocomplete="family-name">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="john.doe@example.com" required autocomplete="email">
                        <label for="phoneNumber">Phone Number</label>
                        <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="234-567-8901" autocomplete="tel">
                        <label class="radio" for="phoneTypeHome"><input type="radio" name="phoneType" value="Home" id="phoneTypeHome" />Home</label>
                        <label class="radio" for="phoneTypeCell"><input type="radio" name="phoneType" value="Mobile" id="phoneTypeCell" />Cell</label>
                        <label class="radio" for="phoneTypeMinistry"><input type="radio" name="phoneType" value="Ministry" id="phoneTypeMinistry" />Ministry</label>
                        <button class="next">Next &rarr;</button>
                    </fieldset>
                    <fieldset class="address">
                        <legend class="visuallyhidden"><h3>Address</h3></legend>
                        <label for="address">Street</label>
                        <input type="text" id="address" name="address" placeholder="123 Anystreet" required autocomplete="address-line1">
                        <label for="address2">Street 2</label>
                        <input type="text" id="address2" name="address2" placeholder="Suite A" autocomplete="address-line2">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" placeholder="Sometown" required autocomplete="address-level2">
                        <label for="state">State</label>
                        <select id="state" name="state" required autocomplete="address-level1">
                            <option>- Select One -</option>
                            <option value="AL">Alabama</option>
                            <option value="AK">Alaska</option>
                            <option value="AZ">Arizona</option>
                            <option value="AR">Arkansas</option>
                            <option value="CA">California</option>
                            <option value="CO">Colorado</option>
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="DC">District of Columbia</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="HI">Hawaii</option>
                            <option value="ID">Idaho</option>
                            <option value="IL">Illinois</option>
                            <option value="IN">Indiana</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="ME">Maine</option>
                            <option value="MD">Maryland</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NV">Nevada</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NM">New Mexico</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="ND">North Dakota</option>
                            <option value="OH">Ohio</option>
                            <option value="OK">Oklahoma</option>
                            <option value="OR">Oregon</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="SD">South Dakota</option>
                            <option value="TN">Tennessee</option>
                            <option value="TX">Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VT">Vermont</option>
                            <option value="VA">Virginia</option>
                            <option value="WA">Washington</option>
                            <option value="WV">West Virginia</option>
                            <option value="WI">Wisconsin</option>
                            <option value="WY">Wyoming</option>
                        </select>
                        <label for="postalCode">ZIP Code</label>
                        <input type="text" id="postalCode" name="postalCode" placeholder="12345" required autocomplete="postal-code" pattern="[0-9]*">
                        <input type="hidden" name="country" value="US" />
                        <button class="back">&larr; Back</button>
                        <button class="next">Next &rarr;</button>
                    </fieldset>
                    <fieldset class="payment">
                        <legend class="visuallyhidden"><h3>Payment Information</h3></legend>
                        <label for="cardnumber">Card Number</label>
                        <input type="text" id="cardnumber" name="cardnumber" autocomplete="cc-name" placeholder="4111 1111 1111 1111" required pattern="[0-9]*">
                        <label for="cc-exp">Expiration Date</label>
                        <input type="text" id="cc-exp-month" name="cc-exp-month" autocomplete="cc-exp-month" placeholder="01" size="2" required pattern="[0-9]*">/
                        <input type="text" id="cc-exp-year" name="cc-exp-year" autocomplete="cc-exp-year" placeholder="2020" size="4" required pattern="[0-9]*">
                        <label for="cvc">Verification Number</label>
                        <input type="text" id="cvc" name="cvc" autocomplete="cc-csc" placeholder="123" required pattern="[0-9]*"><br/>
                        <label class="radio" for="paymentRecurring"><input type="radio" name="paymentSchedule" value="recurring" id="paymentRecurring" checked="checked" />Recurring yearly payment</label>
                        <label class="radio" for="paymentOneTime"><input type="radio" name="paymentSchedule" value="one-time" id="paymentOneTime" />One-time payment</label><br/>
                        <button class="back">&larr; Back</button>
                        <button type="submit">Sign Up &rarr;</button>
                    </fieldset>
                </form>
                <div class="message success" style="display: none;">
                    <h2>Thank you</h2>
                    <p>We&rsquo;ll be in touch soon.</p>
                    <p>In the meantime, check out <a href="http://www.ncll.org/">our website</a>.</p>
                </div>
                <div class="message failure" style="display: none;">
                    <h2>Oops&hellip;</h2>
                    <p>Something went wrong.</p>
                    <p>We did get your contact information, however, and we&rsquo;ll reach out to you as soon as we can.</p>
                    <p>In the meantime, check out <a href="http://www.ncll.org/">our website</a>.</p>
                </div>
            </section><!-- .form -->
            <section class="cta-headers">
                <h1>Secure your Homeschool Membership Today!</h1>
                <h2>As a homeschool member, you get legal support from our trained lawyers.</h2>
            </section>
        </section><!-- .cta-container -->
        <section class="main-container">
            <section class="main wrapper clearfix">
                <article class="main-content">
                    <header>
                        <h1>Welcome, WORLD News Group subscribers!</h1>
                        <img class="advertiser-logo" src="img/World-News-Group-logo.min.svg" alt="World News Group logo" title="World News Group" />
                    </header>
                    <section>
                        <h1>Heading level 1</h1>
                        <p>Qui robusta ristretto, as cup strong, id acerbic, single shot cup, rich, instant, decaffeinated french press beans sugar caffeine at filter crema to go dark at percolator. Single shot, roast medium, plunger pot cortado, whipped caffeine espresso coffee strong, dripper mazagran cortado, extraction eu mazagran so acerbic et con panna aromatic. Breve, cappuccino, chicory arabica spoon, kopi-luwak cup spoon barista eu spoon coffee spoon dark ristretto cup instant white. Brewed pumpkin spice body, est carajillo, eu french press, cream extraction strong spoon, irish id americano sugar spoon cup plunger pot. Acerbic frappuccino, grinder, breve to go sweet con panna, barista aged, crema, organic, in cappuccino wings carajillo flavour. Redeye con panna, aftertaste extra cream sweet dark blue mountain arabica caramelization, foam, skinny, acerbic, lungo latte coffee robusta cream latte.</p>
                        <h2>Heading level 2</h2>
                        <p>Qui robusta ristretto, as cup strong, id acerbic, single shot cup, rich, instant, decaffeinated french press beans sugar caffeine at filter crema to go dark at percolator. Single shot, roast medium, plunger pot cortado, whipped caffeine espresso coffee strong, dripper mazagran cortado, extraction eu mazagran so acerbic et con panna aromatic. Breve, cappuccino, chicory arabica spoon, kopi-luwak cup spoon barista eu spoon coffee spoon dark ristretto cup instant white. Brewed pumpkin spice body, est carajillo, eu french press, cream extraction strong spoon, irish id americano sugar spoon cup plunger pot. Acerbic frappuccino, grinder, breve to go sweet con panna, barista aged, crema, organic, in cappuccino wings carajillo flavour. Redeye con panna, aftertaste extra cream sweet dark blue mountain arabica caramelization, foam, skinny, acerbic, lungo latte coffee robusta cream latte.</p>
                        <h3>Heading level 3</h3>
                        <p>Qui robusta ristretto, as cup strong, id acerbic, single shot cup, rich, instant, decaffeinated french press beans sugar caffeine at filter crema to go dark at percolator. Single shot, roast medium, plunger pot cortado, whipped caffeine espresso coffee strong, dripper mazagran cortado, extraction eu mazagran so acerbic et con panna aromatic. Breve, cappuccino, chicory arabica spoon, kopi-luwak cup spoon barista eu spoon coffee spoon dark ristretto cup instant white. Brewed pumpkin spice body, est carajillo, eu french press, cream extraction strong spoon, irish id americano sugar spoon cup plunger pot. Acerbic frappuccino, grinder, breve to go sweet con panna, barista aged, crema, organic, in cappuccino wings carajillo flavour. Redeye con panna, aftertaste extra cream sweet dark blue mountain arabica caramelization, foam, skinny, acerbic, lungo latte coffee robusta cream latte.</p>
                        <h4>Heading level 4</h4>
                        <p>Qui robusta ristretto, as cup strong, id acerbic, single shot cup, rich, instant, decaffeinated french press beans sugar caffeine at filter crema to go dark at percolator. Single shot, roast medium, plunger pot cortado, whipped caffeine espresso coffee strong, dripper mazagran cortado, extraction eu mazagran so acerbic et con panna aromatic. Breve, cappuccino, chicory arabica spoon, kopi-luwak cup spoon barista eu spoon coffee spoon dark ristretto cup instant white. Brewed pumpkin spice body, est carajillo, eu french press, cream extraction strong spoon, irish id americano sugar spoon cup plunger pot. Acerbic frappuccino, grinder, breve to go sweet con panna, barista aged, crema, organic, in cappuccino wings carajillo flavour. Redeye con panna, aftertaste extra cream sweet dark blue mountain arabica caramelization, foam, skinny, acerbic, lungo latte coffee robusta cream latte.</p>
                        <h5>Heading level 5</h5>
                        <p>Qui robusta ristretto, as cup strong, id acerbic, single shot cup, rich, instant, decaffeinated french press beans sugar caffeine at filter crema to go dark at percolator. Single shot, roast medium, plunger pot cortado, whipped caffeine espresso coffee strong, dripper mazagran cortado, extraction eu mazagran so acerbic et con panna aromatic. Breve, cappuccino, chicory arabica spoon, kopi-luwak cup spoon barista eu spoon coffee spoon dark ristretto cup instant white. Brewed pumpkin spice body, est carajillo, eu french press, cream extraction strong spoon, irish id americano sugar spoon cup plunger pot. Acerbic frappuccino, grinder, breve to go sweet con panna, barista aged, crema, organic, in cappuccino wings carajillo flavour. Redeye con panna, aftertaste extra cream sweet dark blue mountain arabica caramelization, foam, skinny, acerbic, lungo latte coffee robusta cream latte.</p>
                        <h6>Heading level 6</h6>
                        <p>Qui robusta ristretto, as cup strong, id acerbic, single shot cup, rich, instant, decaffeinated french press beans sugar caffeine at filter crema to go dark at percolator. Single shot, roast medium, plunger pot cortado, whipped caffeine espresso coffee strong, dripper mazagran cortado, extraction eu mazagran so acerbic et con panna aromatic. Breve, cappuccino, chicory arabica spoon, kopi-luwak cup spoon barista eu spoon coffee spoon dark ristretto cup instant white. Brewed pumpkin spice body, est carajillo, eu french press, cream extraction strong spoon, irish id americano sugar spoon cup plunger pot. Acerbic frappuccino, grinder, breve to go sweet con panna, barista aged, crema, organic, in cappuccino wings carajillo flavour. Redeye con panna, aftertaste extra cream sweet dark blue mountain arabica caramelization, foam, skinny, acerbic, lungo latte coffee robusta cream latte.</p>

                    </section>
                </article>

            </section><!-- .main -->
        </section> <!-- #main-container -->

<?php include('template/footer.html'); ?>
