



$(document).ready(function() {

    $("#sendBtn").on("click", function(){

        var name = $("#UnameInput").val();
        var email = $("#EmailInput").val();
        var request = $("#RequestInput").val();

        if(!name || !email || !request){
            Swal.fire(
                'Request Error!',
                'One or more fields are empty or invalid',
                'error'
            )
            
        }else{
            console.log("DEMO:");
            console.log(`  Name: ${name}`);
            console.log(`  Email: ${email}`);
            console.log(`  Request: ${request}`);

            Swal.fire(
                'Request Recieved!',
                'Your request has been sent, we will contact you as soon as possible',
                'success'
            )
        }
    });



    var pictures = ['../data/cars/bmw_m4_2023_1.webp', '../data/cars/mercedes_amg_gt_2018_1.webp', '../data/cars/porsche_911_2023_1.webp'];
    var cars = {
        "Bmw M4": {
            "img": "../data/cars/bmw_m4_2023_1.webp",
            "specs": [
                "Year: 2023",
                "Power: 503 HP",
                "0-60: 3.4s",
                "Mileage: 10,000 miles"
            ],
            "price": "Buy: $76,000",
            "rent": "Rent: $799/month"
        },
        "Porsche 911": {
            "img": "../data/cars/porsche_911_2023_1.webp",
            "specs": [
                "Year: 2023",
                "Power: 640 HP",
                "0-60: 3.4s",
                "Mileage: 2,000 miles"
            ],
            "price": "Buy: $217,550",
            "rent": "Rent: $2000/month"
        },
        "Mercedes AMG GT": {
            "img": "../data/cars/mercedes_amg_gt_2018_1.webp",
            "specs": [
                "Year: 2018",
                "Power: 469 HP",
                "0-60: 3.7s",
                "Mileage: 30,000 miles"
            ],
            "price": "Buy: $132,400",
            "rent": "Rent: $1199/month"
        }
    }
    const carList = document.getElementById("container");

    for (const carName in cars) {
        const car = cars[carName];
        const carItem = document.createElement("div");
        carItem.classList.add("car-item");

        const carImg = document.createElement("img");
        carImg.src = car.img;
        carImg.alt = carName;
        carItem.appendChild(carImg);

            var overlay = document.createElement('div');
            overlay.className = 'overlay';
            const carSpecs = document.createElement("ul");
            carSpecs.classList.add("car-specs-list");
            car.specs.forEach((spec) => {
                const specItem = document.createElement("li");

                specItem.textContent = spec;
                carSpecs.appendChild(specItem);
            });
            overlay.appendChild(carSpecs);

            const carLabel1 = document.createElement("div");
            carLabel1.classList.add("buy-price-label");
            carLabel1.textContent = cars[carName]["price"];
            overlay.appendChild(carLabel1);

            const carLabel2 = document.createElement("div");
            carLabel2.classList.add("rent-price-label");
            carLabel2.textContent = cars[carName]["rent"];
            overlay.appendChild(carLabel2);

            const carNameHeader = document.createElement("h3");
            carNameHeader.textContent = carName;
            carNameHeader.classList.add("car-name-label");
            overlay.appendChild(carNameHeader);

        carItem.appendChild(overlay); 
        carList.appendChild(carItem);
    }

});