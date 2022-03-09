/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package konkursFirefox;

import konkurs.*;
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
public class TP48_nije_unet_datum {
    
    public TP48_nije_unet_datum() {
    }

   @Test
    public void registracijaKompanijaTest48() {
        System.setProperty("webdriver.gecko.driver", "C:\\Users\\korisnik\\geckodriver.exe");
        driver = new FirefoxDriver();
        //dohvati nam sajt za testiranje
        
        driver.get(baseUrl);
        driver.manage().window().maximize();
        
        //popunjavanje podataka forme i slanje forme
        String nazivKonkursa = "Junior Software Developer";
        String tip = "Posao";
        String rokZaPrijavu = "";
        String tekstKonkursa = "Veoma odlicna pozicija";
        


        driver.findElement(By.name("loginUsername")).sendKeys("elsys");
        driver.findElement(By.name("loginPassword")).sendKeys("Sifra123$");
        driver.findElement(By.name("loginButton")).click();
        
        driver.findElement(By.linkText("Konkursi")).click();
        
        driver.findElement(By.linkText("DODAJ NOVI KONKURS")).click();
        
        driver.findElement(By.name("nazivKon")).sendKeys(nazivKonkursa);
        //driver.findElement(By.name("tipKonPosao")).click();
        driver.findElement(By.name("rokKon")).sendKeys(rokZaPrijavu);
        driver.findElement(By.name("tekstKon")).sendKeys(tekstKonkursa);
        
        
        
        driver.findElement(By.name("noviKonDugme")).click();
        
         String message = driver.findElement(By.name("rokKon")).getAttribute("required");
        Assert.assertTrue(message.equals("true"));
        
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
