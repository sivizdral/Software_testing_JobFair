/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package kompanijaFirefox;

import kompanija.*;
import static com.mycompany.testovidz1.Testovi.baseUrl;
import static com.mycompany.testovidz1.Testovi.driver;
import org.openqa.selenium.By;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.testng.Assert;
import static org.testng.Assert.*;
import org.testng.annotations.AfterClass;
import org.testng.annotations.AfterMethod;
import org.testng.annotations.BeforeClass;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;

/**
 *
 * @author korisnik
 */
public class TP37_PIB_ima_vise_od_9_cifara {
    
    public TP37_PIB_ima_vise_od_9_cifara() {
    }

  @Test
    public void registracijaKompanijaTest37() {
        System.setProperty("webdriver.gecko.driver", "C:\\Users\\korisnik\\geckodriver.exe");
        driver = new FirefoxDriver();
        //dohvati nam sajt za testiranje
        driver.get(baseUrl);
        driver.manage().window().maximize();
        
        //popunjavanje podataka forme i slanje forme
        String korisnickoIme = "janestreet";
        String sifra = "Mojasifr20!?";
        String nazivKompanije = "Jane Street";
        String grad = "London";
        String adresa = "Devonshire Square 29";
        String email = "info@jane.com";
        String direktor = "John Smith";
        String PIB = "1234567890";
        String brojZaposlenih = "200";
        String vebAdresa = "www.janestreet.com";
        String delatnost = "IT";
        String uzaSpecijalnost = "deonice";
        String poruka = "Please fill out this field.";



        driver.findElement(By.linkText("Kompanija si koja hoće da učestvuje na sajmu? Registruj se!")).click();
        driver.findElement(By.name("regKompanijaUsername")).sendKeys(korisnickoIme);
        driver.findElement(By.name("regKompanijaPassword")).sendKeys(sifra);
        driver.findElement(By.name("regKompanijaPassword2")).sendKeys(sifra);
        driver.findElement(By.name("regKompanijaNaziv")).sendKeys(nazivKompanije);
        driver.findElement(By.name("regKompanijaGrad")).sendKeys(grad);
        driver.findElement(By.name("regKompanijaAdresa")).sendKeys(adresa);
        driver.findElement(By.name("regKompanijaMail")).sendKeys(email);
        driver.findElement(By.name("regKompanijaDirektor")).sendKeys(direktor);
        driver.findElement(By.name("regKompanijaPib")).sendKeys(PIB);
        driver.findElement(By.name("regKompanijaBrZap")).sendKeys(brojZaposlenih);
        driver.findElement(By.name("regKompanijaWww")).sendKeys(vebAdresa);
        driver.findElement(By.name("regKompanijaDelatnost")).sendKeys(delatnost);
        driver.findElement(By.name("regKompanijaSpec")).sendKeys(uzaSpecijalnost);
        
        driver.findElement(By.id("logoKompanija")).sendKeys("C:\\Users\\korisnik\\Desktop\\DZ1\\Projekat 1 - specifikacija\\Square_200x200.png");
        driver.findElement(By.name("regKompanijaButton")).click();
        
        String tekst = driver.findElement(By.id("porukaSadrzaj")).getText();
        Assert.assertTrue(tekst.equals("PIB nije ispravno unet!"));
        
        driver.quit();
    }

    @BeforeClass
    public static void setUpClass() throws Exception {
    }

    @AfterClass
    public static void tearDownClass() throws Exception {
    }

    @BeforeMethod
    public void setUpMethod() throws Exception {
    }

    @AfterMethod
    public void tearDownMethod() throws Exception {
    }
}
