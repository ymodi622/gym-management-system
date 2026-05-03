function sendMail() {
    var params = {
        name : "anonymus",
        email : "willtyfitness@gmail.com",
        message: "Hii, this got really hactic"
    }
    const serviceID = "service_t9pfn8w";
    const templateID = "template_xzvz8xl";
    
    emailjs.send(serviceID,templateID,params)
    .then(
        res =>{
            console.log(res);
            alert("email sent!")
        }
    ).catch((err) => console.log(err))
}
