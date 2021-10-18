function utcToLocal(time) {
  var localTime  = moment.utc(time).toDate();
  return moment(localTime).format('DD-MM-YYYY HH:mm:ss');
}
function formatMoney(value, type) {
    if(!value)  value = 0;
    let val = value;
    if(type == "CL") {
        val = val.toString().replace('.', ',');
        return val.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");         
    }
    if(type == "US") {
      val = val.toString().replace(',', '.');
      return value.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }
}

function generateErrorList(errors) {
  let errList = "";
  Object.keys(errors).forEach(function(key) {
      let keyVal = key.split('.');
      let num = (parseInt(keyVal)+1)
      errList += ((!isNaN(num) && num != undefined) ? (num + ": ") : '') + errors[key] + '\n';
  });
  return errList;
}

function completeSelect(id,count) {
	$(id).empty();
	
	for(var i = 1;i<= count;i++)
	{
	     $('<option />', {value: i, text: i }).appendTo(id);	
	}
}
function validateEmail(email) {
  if(email.trim() == "") {
    return true;
  }
  var re = /\S+@\S+\.\S+/;
  return re.test(email);
}
function revertDate(date) {
  let result = date.split('-');
  return result[2]+'-'+result[1]+'-'+result[0];
}
function calculateFee(total, number,type='floor') {
  if(type == 'floor') fee = Math.floor(total / number);
  else fee = Math.round( (total / number) * 10)/10;
  let values = [];
  let appTotal = fee;
  for (i = 0; i < number; i++) {
      if (i == number - 1) {
          result = total - appTotal;
          values.push(fee + result);
      } else {
          values.push(fee);
          appTotal += fee;
      }
  }
  return values;
}
function rutEsValido(rut) {
  if (!rut || rut.trim().length < 3) return false;
  const rutLimpio = rut.replace(/[^0-9kK-]/g, "");

  if (rutLimpio.length < 3) return false;

  const split = rutLimpio.split("-");
  if (split.length !== 2) return false;

  const num = parseInt(split[0], 10);
  const dgv = split[1];

  const dvCalc = calculateDV(num);
  return dvCalc.toLowerCase() === dgv.toLowerCase();
}
function calculateDV(rut) {
  const cuerpo = `${rut}`;
  // Calcular Dígito Verificador
  let suma = 0;
  let multiplo = 2;

  // Para cada dígito del Cuerpo
  for (let i = 1; i <= cuerpo.length; i++) {
    // Obtener su Producto con el Múltiplo Correspondiente
    const index = multiplo * cuerpo.charAt(cuerpo.length - i);

    // Sumar al Contador General
    suma += index;

    // Consolidar Múltiplo dentro del rango [2,7]
    if (multiplo < 7) {
      multiplo += 1;
    } else {
      multiplo = 2;
    }
  }

  // Calcular Dígito Verificador en base al Módulo 11
  const dvEsperado = 11 - (suma % 11);
  if (dvEsperado === 10) return "k";
  if (dvEsperado === 11) return "0";
  return `${dvEsperado}`;
}