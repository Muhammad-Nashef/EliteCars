///////// Important Notes /////////

------ General Notes ------ 
- we used 1920x1080 as a reference resolution for the project 
    (which is the default for most displays)

- we added two credit cards with different balances (one with 777 balance the other with alot of money)
    so its easier for you to test it, they are available in processPayment.php => verifyDetailsWithCards() 
        ** these balances of these credit cards are constant (for testing purposes) **
        
    Our goal with this is to simulate a real world scenario where the user has a credit card with a balance
    to enable as many purchases as you need (sufficient amount), in the real world it can replaced with API to verify the card and amount.

- we used bootstrap for styling

- we used SweetAlerts for nicer alerts, and also the default alerts

- we added a security layer so the user can't access the admin panel
    without being logged in as an admin, and the user also can't access
    any page that requires logging in

- we assumed that the user/admin inputs throughout the website are valid and correct

------ Account and logging in ------ 
- use username -> admin, password -> admin to log in to the admin panel
- you could also use username -> test1, pass -> pass to see an existing user or you could also sign up for your own


------ Admin ------ 
- As an admin, when adding a car to the collection, try and match the size
    of the image you upload with the existing images for the best results.

Enjoy using the website!
