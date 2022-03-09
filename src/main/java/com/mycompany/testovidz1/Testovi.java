package com.mycompany.testovidz1;

import java.util.logging.Level;
import java.util.logging.Logger;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.chrome.ChromeDriver;
import org.testng.Assert;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

public class Testovi {
    public static String baseUrl = "http://localhost/projekat1/JobFair/login.php";
    public static WebDriver driver;
    
    public static void main(String[] args){
        System.setProperty("webdriver.chrome.driver", "C:\\Users\\korisnik\\chromedriver.exe");
        driver = new ChromeDriver();
        //dohvati nam sajt za testiranje
        driver.get(baseUrl);
        driver.manage().window().maximize();
        
        //popunjavanje podataka forme i slanje forme
        String korisnickoIme = "drasko";
        String sifra = "SIfra123.";
        String ime = "Ivan";
        String prezime = "Cvetic";
        String email = "ivancvetic@outlook.com";
        String telefon = "+381645555555";
        String poruka = "Please fill out this field.";
        TestRegistracija(korisnickoIme, sifra, ime, prezime, email, telefon, poruka);
        //driver.findElement(By.linkText("Log out")).click();
        //LoginUnsTest("drasko","blabla","Nepostojeci korisnik!");
        driver.quit();
    }
    
    public static void TestRegistracija(String korisnickoIme, String sifra, String ime, String prezime, String email, String telefon, String poruka){
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
        
        //String message = driver.findElement(By.name("regStudentUsername")).getAttribute("required");
        driver.findElement(By.name("regStudentButton")).click(); 
        try {
            Thread.sleep(1000);
        } catch (InterruptedException ex) {
            Logger.getLogger(Testovi.class.getName()).log(Level.SEVERE, null, ex);
        }
        //WebDriverWait wait = new WebDriverWait(driver,1000);
        //wait.until(ExpectedConditions.alertIsPresent());
        Alert alert = driver.switchTo().alert();
        
        String message = alert.getText();
        System.out.println(message);
         try {
            Thread.sleep(5000);
        } catch (InterruptedException ex) {
            Logger.getLogger(Testovi.class.getName()).log(Level.SEVERE, null, ex);
        }
        alert.accept();
        
        
        Assert.assertTrue(message.equals("true"));
        
    }
    
    public static void LoginUnsTest(String user, String pass, String poruka){
        
        driver.findElement(By.name("login_korisnickoIme")).sendKeys(user);
        driver.findElement(By.name("login_lozinka")).sendKeys(pass);
        driver.findElement(By.id("dugmePotvrde")).click();
        
        String errorMsg = driver.findElement(By.className("error")).getText();
        
       
        Assert.assertEquals(errorMsg, poruka);
        
        
    }
}
