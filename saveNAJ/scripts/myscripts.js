function wypiszLekarzy() 
{
    var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("lekarze").innerHTML = this.responseText;
				}
			};
			xmlhttp.open("GET", "../phpscripts/wypiszLekarzy.php", true);
			xmlhttp.send();
}
function wypiszPacjentow() {
    var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) 
				{
					document.getElementById("pacjenci").innerHTML = this.responseText;
				}
			};
			xmlhttp.open("GET", "../phpscripts/wypiszPacjentow.php", true);
			xmlhttp.send();
}
function showHideBar() {
	const element = document.getElementById("hiddenMenuForNav");
	const style = window.getComputedStyle(element);

	if (style.display === "none") {
		element.style.display = "block";
	} else {
		element.style.display = "none";
	}
}
window.onclick = function(event) 
{
	var modal = document.getElementById('id01');
	//to wyzej wrzucic ewentualnie poza funkcje, w sensie wyzej?
    if (event.target == modal) 
	{
        modal.style.display = "none";
    }
	if(event.target == document.getElementById("modalNaPacjenta"))
	{
		document.getElementById("modalNaPacjenta").style.display='none';
	}
}
//Funkcja sprawdzająca długość pola "pesel" podczas dodawania pacjenta do bazy danych przez lekarza. Zostawiam na przyszłość, może włączę ale aktualnie preferuję ostrzeżenie w formie czerwonego boxa wyskakującego na górze.
function checkPeselLength() 
{
    var peselInput = document.getElementById('pesel');
    var addButton = document.getElementById('btnDodajUsera');
    
    if (peselInput.value.length === 11) {
        addButton.disabled = false; // Włącz przycisk
        addButton.classList.remove('grayButton'); // Usuń klasę CSS
		addButton.classList.add('blueButton');
    } else {
        addButton.disabled = true; // Wyłącz przycisk
		addButton.classList.remove('blueButton'); // Usuń klasę CSS
		addButton.classList.add('grayButton');
    }
}
function closeErrorBox()
{
	var box = document.getElementById("errorNotification");
	box.style.display = "none";
}
function closeNotificationBox()
{
	var box = document.getElementById("goodNotification");
	box.style.display = "none";
}
function ukryjSchowajPacjenci()
{
	var divMoj=document.getElementById("tabelaStatyHolderPacjenci");
	if(divMoj.style.display==='none')
	{
		divMoj.style.display='inline-block';
		document.getElementById("btnPacjenci").innerHTML='<span>Ukryj informacje</span>&nbsp;<span class="material-symbols-outlined">expand_circle_up</span>';
		
	}
	else
	{
		divMoj.style.display='none';
		document.getElementById("btnPacjenci").innerHTML='<span>Dowiedz się więcej</span>&nbsp;<span class="material-symbols-outlined">expand_circle_down</span>';
		if(document.getElementById("tabelaStatyHolderWizyty").style.display=='none')
		{
			document.getElementById("doktorSpacja1").style.display='none';
		}
	}
}
function ukryjSchowajWizyty()
{
	var divMoj=document.getElementById("tabelaStatyHolderWizyty");
	
	if(divMoj.style.display==='none')
	{
		divMoj.style.display='inline-block';
		document.getElementById("btnWizyty").innerHTML='<span>Ukryj informacje</span>&nbsp;<span class="material-symbols-outlined">expand_circle_up</span>';
		document.getElementById("doktorSpacja1").style.display='block';
		document.getElementById("doktorSpacja2").style.display='block';
	}
	else
	{
		divMoj.style.display='none';
		document.getElementById("btnWizyty").innerHTML='<span>Dowiedz się więcej</span>&nbsp;<span class="material-symbols-outlined">expand_circle_down</span>';
		if(document.getElementById("tabelaStatyHolderBadania").style.display=='none')
		{
			document.getElementById("doktorSpacja2").style.display='none';
		}
		document.getElementById("doktorSpacja1").style.display='none';
	}
}
function ukryjSchowajBadania()
{
	var divMoj=document.getElementById("tabelaStatyHolderBadania");
	
	if(divMoj.style.display==='none')
	{
		document.getElementById("btnBadania").innerHTML='<span>Ukryj informacje</span>&nbsp;<span class="material-symbols-outlined">expand_circle_up</span>';
		divMoj.style.display='inline-block';
		document.getElementById("doktorSpacja2").style.display='block';
		document.getElementById("doktorSpacja3").style.display='block';
	}
	else
	{
		divMoj.style.display='none';
		document.getElementById("btnBadania").innerHTML='<span>Dowiedz się więcej</span>&nbsp;<span class="material-symbols-outlined">expand_circle_down</span>';
		if(document.getElementById("tabelaStatyHolderRecepty").style.display=='none')
		{
			document.getElementById("doktorSpacja3").style.display='none';
		}
		document.getElementById("doktorSpacja2").style.display='none';
		
	}
}
function ukryjSchowajRecepty()
{
	var divMoj=document.getElementById("tabelaStatyHolderRecepty");
	
	if(divMoj.style.display==='none')
	{
		divMoj.style.display='inline-block';
		document.getElementById("doktorSpacja3").style.display='block';
		document.getElementById("doktorSpacja4").style.display='block';
	}
	else
	{
		divMoj.style.display='none';
		if(document.getElementById("tabelaStatyHolderChoroby").style.display=='none')
		{
			document.getElementById("doktorSpacja4").style.display='none';
		}
		document.getElementById("doktorSpacja3").style.display='none';
		
	}
}
function ukryjSchowajChoroby()
{
	var divMoj=document.getElementById("tabelaStatyHolderChoroby");
	
	if(divMoj.style.display==='none')
	{
		divMoj.style.display='inline-block';
		
		document.getElementById("doktorSpacja3").style.display='block';
		document.getElementById("doktorSpacja4").style.display='block';
	}
	else
	{
		divMoj.style.display='none';
		document.getElementById("doktorSpacja3").style.display='none';
		document.getElementById("doktorSpacja4").style.display='none';
	}
}
function zmienWyszukaj()
{
	document.getElementById("statyKontener").style.display='none';
	document.getElementById("wyszukiwaniePacjenta").style.display='flex';
}
function zmienStatystyki()
{
	document.getElementById("statyKontener").style.display='flex';
	document.getElementById("wyszukiwaniePacjenta").style.display='none';
}