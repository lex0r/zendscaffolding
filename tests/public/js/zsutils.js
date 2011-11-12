function ssfResetForm(form)
{
    for(i = 0; i < form.elements.length; i++) {
        fieldType = form.elements[i].type.toLowerCase();
        switch (fieldType) {
            case "text": case "password":
            case "textarea": case "hidden":
                form.elements[i].value = "";
                break;

            case "radio": case "checkbox":
                if (form.elements[i].checked)
                    form.elements[i].checked = false;
                break;

            case "select-one": case "select-multi":
                form.elements[i].selectedIndex = -1;
                break;

            default: break;
        }
    }
}