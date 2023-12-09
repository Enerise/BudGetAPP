let myChart = document.getElementById('myChart').getContext('2d');

const labelTooltip = () => {
    return '';
}

function addData(chart, label, newData) {
    chart.data.labels.push(label);
    //console.log(color);
    chart.data.datasets.forEach((dataset) => {
        dataset.data.push(newData);
        // dataset.backgroundColor.push("rgba(252, 233, 0, 1)");
    });

    chart.update();
}

function removeData(chart) {

    let total = chart.data.labels.length;

    while (total >= 0) {
        chart.data.labels.pop();
        chart.data.datasets[0].data.pop();
        //chart.data.datasets[0].backgroundColor.pop();
        total--;
    }

    chart.update();
}


let pieChart = new Chart(myChart, {
    type: 'pie',
    data: {
        datasets: [{
            label: '',
            data: [],
            backgroundColor: [
                "rgba(255, 179, 56, 1)",
                "rgba(255, 225, 56, 1)",
                "rgba(255, 202, 56, 1)",
                "rgba(255, 146, 56, 1)",
                "rgba(216, 208, 56, 1)",
                "rgba(179, 255, 56, 1)",
                "rgba(56, 255, 91, 1)",
                "rgba(255, 253, 56, 1)",
                "rgba(94, 255, 55, 1)",
                "rgba(56, 255, 159, 1)",
            ]
        }]
    },
    options: {
        plugins: {
            tooltip: {
                titleMarginBottom: 0,
                callbacks: {
                    title: (ctx) => {
                        return ctx[0].label + ": " + ctx[0].raw + " zł";
                    },
                    label: labelTooltip
                }
            }
        }
    }
});
let periodField = document.getElementById("selectPeriod");

let period = "currentMonth", particulIncomes, particularExpenses, sumOfAmountParticularCategoryIncomes, sumOfAmountParticularCategoryExpenses, dateStart = 1, dateEnd = 1, howManyExpenses, particulIncomesInBox, particularExpensesInBox, howManyParticularExpenses, howManyParticularIncomes, calculatedBalance;

let incomeBoxToRemove;
let expenseBoxToRemove;

let incomeBox = document.getElementById("incomeBox");
let expenseBox = document.getElementById("expenseBox");
let selectBox = document.getElementById("periodBox");
let titleToChange = document.getElementById("titleOfPeriod");

let balanceValueField = document.getElementById("balanceValue");
let balanceInfoField = document.getElementById("balanceInfo");

let dateStartField = document.getElementById("inputDateStart");
let dateEndField = document.getElementById("inputDateEnd");
let buttonShowBalance = document.getElementById("showBalance");


periodField.addEventListener('change', async () => {

    period = await periodField.value;

    switch (period) {
        case "currentMonth":
            expenseBoxToRemove.remove();
            incomeBoxToRemove.remove();
            createDivBoxExpense();
            createDivBoxIncome();
            await getData(period);
            titleToChange.innerHTML = "Bieżący miesiąc"
            break;
        case "lastMonth":
            expenseBoxToRemove.remove();
            incomeBoxToRemove.remove();
            createDivBoxExpense();
            createDivBoxIncome();
            await getData(period);
            titleToChange.innerHTML = "Poprzedni miesiąc"
            break;
        case "currentYear":
            expenseBoxToRemove.remove();
            incomeBoxToRemove.remove();
            createDivBoxExpense();
            createDivBoxIncome();
            await getData(period);
            titleToChange.innerHTML = "Bieżący rok"
            break;
        case "custom":
            $('#modalOfCustomDate').modal('show');
            break;
    }
})

buttonShowBalance.addEventListener('click', async () => {

    if ((dateStartField.value == "") || (dateEndField.value == "")) {
        alert("Proszę wybrać poprawną datę")
    } else {
        expenseBoxToRemove.remove();
        incomeBoxToRemove.remove();
        createDivBoxExpense();
        createDivBoxIncome();
        dateStart = dateStartField.value;
        dateEnd = dateEndField.value;
        titleToChange.innerHTML = `Okres niestandardowy (${dateStart} - ${dateEnd})`;
        await getData(period);
        $('#modalOfCustomDate').modal('hide');
    }
})


const createDivBoxExpense = () => {
    const newDivElement = document.createElement("div");
    newDivElement.id = "toRemoveEx";
    expenseBox.appendChild(newDivElement);
    expenseBoxToRemove = document.getElementById("toRemoveEx");
}

const createDivBoxIncome = () => {
    const newDivElement = document.createElement("div");
    newDivElement.id = "toRemoveIn";
    incomeBox.appendChild(newDivElement);
    incomeBoxToRemove = document.getElementById("toRemoveIn");
}

const getData = async (period) => {


    particularIncomes = await getParticularIncomes(period);
    particularExpenses = await getParticularExpenses(period);
    sumAmountForCategoriesOfIncomes = await getSumAmountForCategoriesOfIncomes(period);
    sumAmountForCategoriesOfExpenses = await getSumAmountForCategoriesOfExpenses(period);

    renderView();

}

const renderView = async () => {
    removeData(pieChart);
    await renderBoxOfIncomes();
    await renderBoxOfExpenses();
    await renderBoxOfBalance();
}

let iconArrow = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' class='bi bi-arrow-right d-inline-block align-text-top' viewBox='2 0.25 17 17'>" + "<path fill-rule='evenodd' d='M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z'/> </svg>";

let iconPen = "<svg xmlns= 'http://www.w3.org/2000/svg ' width= '26' height= '26' fill= 'black ' class= 'bi bi-pen-fill d-inline-flex' viewBox= '-6 -3 25 25 '> <path d= 'm13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001z '/>";

let iconBen = "<svg xmlns= 'http://www.w3.org/2000/svg ' width= '18 ' height= '18 ' fill= 'currentColor' class= 'bi bi-trash-fill ' viewBox= '0 2 16 16'> <path d= 'M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z ' />"

//expense
let amountFieldChange = document.getElementById("inputAmountExpenseChange");
let dateFieldChange = document.getElementById("inputDateExpenseChange");
let paymentFieldChange = document.getElementById("selectPaymentExpenseChange");
let categoryFieldChange = document.getElementById("selectCategoryExpenseChange");
let commentFieldChange = document.getElementById("changeCommentExpense");
let expenseIndexField = document.getElementById("expenseIndex");
let buttonChangeExpense = document.getElementById("changeExpense");
let buttonDeleteExpense = document.getElementById("deleteExpense");
let amountFieldDelete = document.getElementById("amountOfDeleteExpense");
let dateFieldDelete = document.getElementById("dateOfDeleteExpense");


const renderBoxOfExpenses = async () => {


    for (let i = 0; i < sumAmountForCategoriesOfExpenses.length; i++) {
        const newH5Element = document.createElement("h5");
        newH5Element.innerHTML =
            sumAmountForCategoriesOfExpenses[i].category + `: ` + sumAmountForCategoriesOfExpenses[i].amount + " zł";
        //var r = Math.floor(Math.random() * 255);
        //var g = Math.floor(Math.random() * 255);

        addData(pieChart, sumAmountForCategoriesOfExpenses[i].category, sumAmountForCategoriesOfExpenses[i].amount);

        expenseBoxToRemove.appendChild(newH5Element);
        for (let j = 0; j < particularExpenses.length; j++) {
            if (sumAmountForCategoriesOfExpenses[i].category == particularExpenses[j].category) {
                expenseCategory = particularExpenses[j].category;
                expenseAmount = particularExpenses[j].amount;
                expensePayment = particularExpenses[j].payment;
                expenseDate = particularExpenses[j].date;
                expenseComment = particularExpenses[j].comment;
                expenseID = particularExpenses[j].expense_index;

                const newSVG1Element = document.createElement("svg");
                newSVG1Element.classList.add("d-inline-block");
                newSVG1Element.innerHTML = iconArrow;
                expenseBoxToRemove.appendChild(newSVG1Element);
                const newPElement = document.createElement("p");
                newPElement.classList.add("d-inline-block");
                newPElement.innerHTML = particularExpenses[j].date + ` ` + particularExpenses[j].amount + " zł " + particularExpenses[j].comment;
                expenseBoxToRemove.appendChild(newPElement);

                const newSVG2Element = document.createElement("svg");
                newSVG2Element.classList.add("d-inline-flex");
                newSVG2Element.classList.add("changeExpenses");
                newSVG2Element.setAttribute("category", `${expenseCategory}`);
                newSVG2Element.setAttribute("payment", `${expensePayment}`);
                newSVG2Element.setAttribute("amount", `${expenseAmount}`);
                newSVG2Element.setAttribute("date", `${expenseDate}`);
                newSVG2Element.setAttribute("comment", `${expenseComment}`);
                newSVG2Element.setAttribute("expenseID", `${expenseID}`);
                newSVG2Element.style = "cursor: pointer";
                newSVG2Element.id = `expensePen${i} `;
                newSVG2Element.innerHTML = iconPen;
                expenseBoxToRemove.appendChild(newSVG2Element);

                const newSVG3Element = document.createElement("svg");
                newSVG3Element.classList.add("d-inline-flex");
                newSVG3Element.classList.add("deleteExpenses");
                newSVG3Element.setAttribute("category", `${expenseCategory}`);
                newSVG3Element.setAttribute("payment", `${expensePayment}`);
                newSVG3Element.setAttribute("amount", `${expenseAmount}`);
                newSVG3Element.setAttribute("date", `${expenseDate}`);
                newSVG3Element.setAttribute("comment", `${expenseComment}`);
                newSVG3Element.setAttribute("expenseID", `${expenseID}`);
                newSVG3Element.style = "cursor: pointer";
                newSVG3Element.id = `expenseBen${i} `;
                newSVG3Element.innerHTML = iconBen;
                expenseBoxToRemove.appendChild(newSVG3Element);
                const newBRElement = document.createElement("br");
                expenseBoxToRemove.appendChild(newBRElement);
            }
        }
    }
    document.querySelectorAll('svg.changeExpenses').forEach(element => {
        element.addEventListener('click', () => {
            $('#modalOfChangeExpense').modal('show');
            amountFieldChange.value = element.getAttribute('amount');
            dateFieldChange.value = element.getAttribute('date');
            paymentFieldChange.value = element.getAttribute('payment');
            categoryFieldChange.value = element.getAttribute('category');
            commentFieldChange.value = element.getAttribute('comment');
            expenseIndexField.value = element.getAttribute('expenseID');

        })
    })



    document.querySelectorAll('svg.deleteExpenses').forEach(element => {
        element.addEventListener('click', () => {
            $('#modalOfDeleteExpense').modal('show');
            expenseIndexField.value = element.getAttribute('expenseID');
            amountFieldDelete.innerHTML = "&nbsp;" + element.getAttribute('amount');
            dateFieldDelete.innerHTML = "&nbsp;" + element.getAttribute('date');

        })
    })
}

buttonChangeExpense.addEventListener('click', async () => {

    if (commentFieldChange.value == "") {
        commentFieldChange.value = "empty";
    }

    if ((amountFieldChange.value <= 0) || (dateFieldChange.value == "")) {
        alert("Nie można pozostawić pola kwoty i daty pustej")
    }
    else {
        updateParticularExpenses(period, amountFieldChange.value, dateFieldChange.value, paymentFieldChange.value, categoryFieldChange.value, commentFieldChange.value, expenseIndexField.value)

        expenseBoxToRemove.remove();
        incomeBoxToRemove.remove();
        createDivBoxExpense();
        createDivBoxIncome();
        $('#modalOfChangeExpense').modal('hide');
        let text = "Sukces! Udało się zmienić wybrany wydatek";
        renderBoxOfAlerts(text);
        getData(period);
    }


})

buttonDeleteExpense.addEventListener('click', async () => {

    $('#modalOfDeleteExpense').modal('hide');
    deleteParticularExpenses(period, expenseIndexField.value);
    expenseBoxToRemove.remove();
    incomeBoxToRemove.remove();
    createDivBoxExpense();
    createDivBoxIncome();
    let text = "Sukces! Udało się usunąć wybrany wydatek";
    renderBoxOfAlerts(text);
    getData(period);
})

//income
let amountFieldChangeIncome = document.getElementById("inputAmountIncomeChange");
let dateFieldChangeIncome = document.getElementById("inputDateIncomeChange");
let categoryFieldChangeIncome = document.getElementById("inputCategoryIncomeChange");
let commentFieldChangeIncome = document.getElementById("changeCommentIncome");
let incomeIndexField = document.getElementById("incomeIndex");
let buttonChangeIncome = document.getElementById("changeIncome");
let buttonDeleteIncome = document.getElementById("deleteIncome");
let amountFieldDeleteIncome = document.getElementById("amountOfDeleteEIncome");
let dateFieldDeleteIncome = document.getElementById("dateOfDeleteIncome");

const renderBoxOfIncomes = async () => {

    for (let i = 0; i < sumAmountForCategoriesOfIncomes.length; i++) {
        const newH5Element = document.createElement("h5");
        newH5Element.textContent = sumAmountForCategoriesOfIncomes[i].category + `: ` + sumAmountForCategoriesOfIncomes[i].amount + " zł";
        incomeBoxToRemove.appendChild(newH5Element);
        for (let j = 0; j < particularIncomes.length; j++) {
            if (sumAmountForCategoriesOfIncomes[i].category == particularIncomes[j].category) {
                incomeID = particularIncomes[j].income_index;
                incomeCategory = particularIncomes[j].category;
                incomeAmount = particularIncomes[j].amount;
                incomeDate = particularIncomes[j].date;
                incomeComment = particularIncomes[j].comment;
                const newSVG1Element = document.createElement("svg");
                newSVG1Element.classList.add("d-inline-block");
                newSVG1Element.innerHTML = iconArrow;
                incomeBoxToRemove.appendChild(newSVG1Element);
                const newPElement = document.createElement("p");
                newPElement.classList.add("d-inline-block");
                newPElement.textContent = particularIncomes[j].date + ` ` + particularIncomes[j].amount + " zł " + particularIncomes[j].comment;
                incomeBoxToRemove.appendChild(newPElement);
                const newSVG2Element = document.createElement("svg");
                newSVG2Element.classList.add("d-inline-flex");
                newSVG2Element.classList.add("changeIncomes");
                newSVG2Element.setAttribute("category", `${incomeCategory}`);
                newSVG2Element.setAttribute("amount", `${incomeAmount}`);
                newSVG2Element.setAttribute("date", `${incomeDate}`);
                newSVG2Element.setAttribute("comment", `${incomeComment}`);
                newSVG2Element.setAttribute("incomeID", `${incomeID}`);
                newSVG2Element.style = "cursor: pointer";
                newSVG2Element.id = `incomePen${i} `;
                newSVG2Element.innerHTML = iconPen;
                incomeBoxToRemove.appendChild(newSVG2Element);
                const newSVG3Element = document.createElement("svg");
                newSVG3Element.classList.add("d-inline-flex");
                newSVG3Element.classList.add("deleteIncomes");
                newSVG3Element.setAttribute("category", `${incomeCategory}`);
                newSVG3Element.setAttribute("amount", `${incomeAmount}`);
                newSVG3Element.setAttribute("date", `${incomeDate}`);
                newSVG3Element.setAttribute("comment", `${incomeComment}`);
                newSVG3Element.setAttribute("incomeID", `${incomeID}`);
                newSVG3Element.style = "cursor: pointer";
                newSVG3Element.id = `incomeBen${i}`;
                newSVG3Element.innerHTML = iconBen;
                incomeBoxToRemove.appendChild(newSVG3Element);
                const newBRElement = document.createElement("br");
                incomeBoxToRemove.appendChild(newBRElement);
            }
        }
    }

    document.querySelectorAll('svg.changeIncomes').forEach(element => {
        element.addEventListener('click', () => {
            $('#modalOfChangeIncome').modal('show');
            amountFieldChangeIncome.value = element.getAttribute('amount');
            dateFieldChangeIncome.value = element.getAttribute('date');
            categoryFieldChangeIncome.value = element.getAttribute('category');
            commentFieldChangeIncome.value = element.getAttribute('comment');
            incomeIndexField.value = element.getAttribute('incomeID');

        })
    })

    document.querySelectorAll('svg.deleteIncomes').forEach(element => {
        element.addEventListener('click', () => {
            $('#modalOfDeleteIncome').modal('show');
            incomeIndexField.value = element.getAttribute('incomeID');
            amountFieldDeleteIncome.innerHTML = "&nbsp;" + element.getAttribute('amount');
            dateFieldDeleteIncome.innerHTML = "&nbsp;" + element.getAttribute('date');



        })
    })
}

buttonChangeIncome.addEventListener('click', async () => {

    if (commentFieldChangeIncome.value == "") {
        commentFieldChangeIncome.value = "empty";
    }

    if ((amountFieldChangeIncome.value <= 0) || (dateFieldChangeIncome.value == "")) {
        alert("Nie można pozostawić pola kwoty i daty pustej")
    }
    else {

        updateParticularIncomes(period, amountFieldChangeIncome.value, dateFieldChangeIncome.value, categoryFieldChangeIncome.value, commentFieldChangeIncome.value, incomeIndexField.value);
        expenseBoxToRemove.remove();
        incomeBoxToRemove.remove();
        createDivBoxExpense();
        createDivBoxIncome();
        $('#modalOfChangeIncome').modal('hide');
        let text = "Sukces! Udało się zmienić wybrany przychód";
        renderBoxOfAlerts(text);
        getData(period);
    }
})

buttonDeleteIncome.addEventListener('click', async () => {

    $('#modalOfDeleteIncome').modal('hide');
    deleteParticularIncomes(period, incomeIndexField.value);
    expenseBoxToRemove.remove();
    incomeBoxToRemove.remove();
    createDivBoxExpense();
    createDivBoxIncome();
    let text = "Sukces! Udało się usunąć wybrany przychód";
    renderBoxOfAlerts(text);
    getData(period);
})

const renderBoxOfBalance = async () => {
    calculatedBalance = await getSumOfBalance(period);

    if (calculatedBalance > 0) {
        balanceValueField.innerHTML = `Świetnie! Jesteś <span span class=fw-bold> ${calculatedBalance} zł</span> na plusie.`;
        balanceInfoField.innerHTML = "Tak trzymaj!";
    } else if (calculatedBalance < 0) {
        balanceValueField.innerHTML = `Jesteś <span span class="fw-bold"> ${calculatedBalance} zł</span> na minusie.`;
        balanceInfoField.innerHTML = "Spróbuj przejrzeć wydatki i przychody - może można gdzieś zaoszczędzić";
    } else if (calculatedBalance == 0) {
        balanceValueField.innerHTML = `Twój bilans wynosi <span span class="fw-bold"> ${calculatedBalance} zł</span> `;
        balanceInfoField.innerHTML = "Spróbuj przejrzeć wydatki i przychody - może można gdzieś zaoszczędzić";
    }

};

let alertsField = document.getElementById("alertBox");

const renderBoxOfAlerts = async (text) => {

    const newDivElement = document.createElement("div");
    newDivElement.classList.add("alertBox");
    newDivElement.id = `alertBoxToDelete`
    alertsField.appendChild(newDivElement);

    let alertsFieldToRemove = await document.getElementById("alertBoxToDelete");
    const newPElement = document.createElement("p");
    newPElement.classList.add("my-3");
    newPElement.classList.add("text-center");
    newPElement.innerHTML = `${text}`;
    alertsFieldToRemove.appendChild(newPElement);

    setTimeout(removeBox, 9000);
};

const removeBox = async () => {
    await alertBoxToDelete.remove();
}



const updateParticularIncomes = async (period, amount, date, category, comment, incomeID) => {
    try {
        const res = await fetch(`../api/updateParticularIncomes/${period}/${amount}/${date}/${category}/${comment}/${incomeID}/${dateStart}/${dateEnd}`);
        const data = await res.json();
        return data;
    } catch (e) {
        console.log('Error', e);
    }
}

const deleteParticularIncomes = async (period, incomeID) => {
    try {
        const res = await fetch(`../api/deleteParticularIncomes/${period}/${incomeID}/${dateStart}/${dateEnd}`);
        const data = await res.json();
        return data;
    } catch (e) {
        console.log('Error', e);
    }
}

const updateParticularExpenses = async (period, amount, date, payment, category, comment, expenseID) => {
    try {
        const res = await fetch(`../api/updateParticularExpenses/${period}/${amount}/${date}/${payment}/${category}/${comment}/${expenseID}/${dateStart}/${dateEnd}`);
        const data = await res.json();
        return data;
    } catch (e) {
        console.log('Error', e);
    }
}

const deleteParticularExpenses = async (period, expenseID) => {
    try {
        const res = await fetch(`../api/deleteParticularExpenses/${period}/${expenseID}/${dateStart}/${dateEnd}`);
        const data = await res.json();
        return data;
    } catch (e) {
        console.log('Error', e);
    }
}

const getParticularIncomes = async (period) => {
    try {
        const res = await fetch(`../api/particularIncomes/${period}/${dateStart}/${dateEnd}`);
        const data = await res.json();
        return data;
    } catch (e) {
        console.log('Error', e);
    }
}

const getParticularExpenses = async (period) => {
    try {
        const res = await fetch(`../api/particularExpenses/${period}/${dateStart}/${dateEnd}`);
        const data = await res.json();
        return data;
    } catch (e) {
        console.log('Error', e);
    }
}

const getSumAmountForCategoriesOfIncomes = async (period) => {
    try {
        const res = await fetch(`../api/sumOfAmountIncomes/${period}/${dateStart}/${dateEnd}`);
        const data = await res.json();
        return data;
    } catch (e) {
        console.log('Error', e);
    }
}

const getSumAmountForCategoriesOfExpenses = async (period) => {
    try {
        const res = await fetch(`../api/sumOfAmountExpenses/${period}/${dateStart}/${dateEnd}`);
        const data = await res.json();
        return data;
    } catch (e) {
        console.log('Error', e);
    }
}

const getSumOfBalance = async (period) => {
    try {
        const res = await fetch(`../api/balance/${period}/${dateStart}/${dateEnd}`);
        const data = await res.json();
        return data;
    } catch (e) {
        console.log('Error', e);
    }
}

createDivBoxExpense();
createDivBoxIncome();
getData(period);


