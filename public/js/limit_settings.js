let categoryField = document.getElementById("selectCategoryForLimit");
let limitField = document.getElementById("inputLimit");

let category;

categoryField.addEventListener('change', async () => {
    category = await categoryField.value;
    await getLimitForCategory(category);
})

const getLimitForCategory = async (category) => {
    try {
        const res = await fetch(`../api/limit/${category}`);
        const data = await res.json();
        if (data != '') {
            limitField.value = `${data}`
        } else {
            limitField.value = '';
            limitField.placeholder = "Brak limitu dla tej kategorii.";
        }
        return data;
    } catch (e) {
        console.log('Error', e);
    }
}