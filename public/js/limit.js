let categoryField = document.getElementById("selectCategory");
let dateField = document.getElementById("inputDate");
let amountField = document.getElementById("inputAmount");

let limitField = document.getElementById("categoryLimit");
let sumOfExpensesField = document.getElementById("spendCategoryLimit");
let restMoneyToSpend = document.getElementById("restLimitToSpend");

let category, date, amount, limit, monthlyExpenses, increment;

const currentDate = () => {
    const currentDate = new Date();
    let day = currentDate.getDate();
    let month = currentDate.getMonth() + 1;
    let year = currentDate.getFullYear();
    return date = year + "-" + month + "-" + day;
}

const categoryAction = async (category) => {
    date = currentDate();
    limit = getLimitForCategory(category);
    eventsAction();

}

const getLimitForCategory = async (category) => {
    try {
        const res = await fetch(`../api/limit/${category}`);
        const data = await res.json();
        if (data != '') {
            limitField.innerHTML = "Twój limit ustawiony jest na " + "<span class='class = fw-bold'>" + `${data} ` + "</span>" + "zł";
        } else {
            limitField.innerHTML = "Nie ustawiono limitu dla tej kategorii";
            increment = 1;
        }

        return data;
    } catch (e) {
        console.log('Error', e);
    }
}

categoryField.addEventListener('change', async () => {
    category = await categoryField.value;
    await categoryAction(category);
})

dateField.addEventListener('change', async () => {
    date = await dateField.value;
    if (date == '') {
        date = undefined;
        sumOfExpensesField.innerHTML = `Wymagany wybór kategorii i daty`;
    } else {
        date = await dateField.value;
        eventsAction();
    }
})

amountField.addEventListener('input', async () => {

    amount = await amountField.value;
    await eventsAction();

})

const eventsAction = () => {

    if ((category != undefined) && (date != undefined)) {
        dateAction(category, date);
    }

    if ((category != undefined) && (date != undefined) && (amount != undefined)) {
        amountAction(amount);
    }
}

const dateAction = async (category, date) => {
    monthlyExpenses = await getMonthlyExpenses(category, date);
}

const getMonthlyExpenses = async (category, date) => {
    try {
        const res = await fetch(`../api/monthlyExpenses/${category}/${date}`);
        const data = await res.json();
        sumOfExpensesField.innerHTML = "Suma wydatków w tym miesiącu wynosi " + "<span class='class = fw-bold'>" + `${data} ` + "</span>" + "zł";
        return data;
    } catch (e) {
        console.log('Error', e);
    }
}

const amountAction = async (amount) => {
    let limit = await getLimitForCategory(category);
    let limitValue = await getMonthlyExpenses(category, date);
    let calculatedLimit = limit - amount - limitValue;


    if (increment == 1) {
        restMoneyToSpend.style.color = "black";
        restMoneyToSpend.innerHTML = "Nie ustawiono limitu dla tej kategorii"
        increment = 0;
    } else {
        if (calculatedLimit > 0) {
            restMoneyToSpend.style.color = "black";
            restMoneyToSpend.innerHTML = `Możesz jeszcze wydać ` + "<span class='class = fw-bold'>" + `${calculatedLimit}` + "</span>" + ` zł w tym miesiącu ` + "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-emoji-smile d-inline-block align-text-top' viewBox='0 0 16 16'>" +
                "<path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'/>" +
                "<path d='M4.285 9.567a.5.5 0 0 1 .683.183A3.498 3.498 0 0 0 8 11.5a3.498 3.498 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.498 4.498 0 0 1 8 12.5a4.498 4.498 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z'/>" +
                "</svg>";
        } else if (calculatedLimit < 0) {
            restMoneyToSpend.innerHTML = `Przekroczony został już limit w tym miesiącu. Jesteś ` + "<span class='class = fw-bold'>" + `${calculatedLimit}` + "</span>" + ` zł na minusie ` + "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-emoji-frown d-inline-block align-text-top' viewBox='0 0 16 16'>" +
                "<path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'/>" +
                "<path d='M4.285 12.433a.5.5 0 0 0 .683-.183A3.498 3.498 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.498 4.498 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z'/>" +
                "</svg>";
            restMoneyToSpend.style.color = "red";
        }

    }


}



