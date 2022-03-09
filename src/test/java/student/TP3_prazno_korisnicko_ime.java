package student;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

import static com.mycompany.testovidz1.Testovi.TestRegistracija;
import static com.mycompany.testovidz1.Testovi.baseUrl;
import static com.mycompany.testovidz1.Testovi.driver;
import org.openqa.selenium.By;
import org.openqa.selenium.chrome.ChromeDriver;
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
public class TP3_prazno_korisnicko_ime {
    
    public TP3_prazno_korisnicko_ime() {
    }

    @Test
    public void registracijaStudentTest3() {
        System.setProperty("webdriver.chrome.driver", "C:\\Users\\korisnik\\chromedriver.exe");
        driver = new ChromeDriver();
        //dohvati nam sajt za testiranje
        driver.get(baseUrl);
        driver.manage().window().maximize();
        
        //popunjavanje podataka forme i slanje forme
        String korisnickoIme = "ivancvetic6";
        String sifra = "Mojasifr20!?";
        String ime = "Ivan";
        String prezime = "Cvetic";
        String email = "ivancvetic@outlook.com";
        String telefon = "+381645555555";
        String poruka = "Please fill out this field.";

        driver.findElement(By.linkText("Student si, a nemaš nalog? Registruj se!")).click();
        //driver.findElement(By.name("regStudentUsername")).sendKeys(korisnickoIme);
        driver.findElement(By.name("regStudentPassword")).sendKeys(sifra);
        driver.findElement(By.name("regStudentPassword2")).sendKeys(sifra);
        driver.findElement(By.name("regStudentIme")).sendKeys(ime);
        driver.findElement(By.name("regStudentPrezime")).sendKeys(prezime);
        driver.findElement(By.name("regStudentMail")).sendKeys(email);
        driver.findElement(By.name("regStudentTel")).sendKeys(telefon);
        driver.findElement(By.name("regStudentGodStudija")).sendKeys("3");
          
        driver.findElement(By.id("slikaStudent")).sendKeys("C:\\Users\\korisnik\\Desktop\\DZ1\\Projekat 1 - specifikacija\\Square_200x200.png");
        driver.findElement(By.name("regStudentButton")).click();
        String message = driver.findElement(By.name("regStudentUsername")).getAttribute("required");
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
