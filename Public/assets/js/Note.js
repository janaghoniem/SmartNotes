// document.addEventListener("DOMContentLoaded", function() {
//     var elements = document.querySelectorAll(".panel");
//     var win = window;
  
//     function visible(el, partial) {
//       var viewTop = win.scrollY;
//       var viewBottom = viewTop + win.innerHeight;
//       var top = el.getBoundingClientRect().top + win.scrollY;
//       var bottom = top + el.clientHeight;
//       var compareTop = partial ? bottom : top;
//       var compareBottom = partial ? top : bottom;
  
//       return ((compareBottom <= viewBottom) && (compareTop >= viewTop));
//     }
  
//     elements.forEach(function(el) {
//       if (visible(el, true)) {
//         el.classList.add("already-visible");
//       }
//     });
  
//     win.addEventListener("scroll", function() {
//       elements.forEach(function(el) {
//         if (visible(el, true)) {
//           el.classList.add("come-in");
//         }
//       });
//     });
  
//     document.querySelector('.summarize').addEventListener('click', function() {
//       const summarizedElement = document.querySelector('.summaried-class');
//       const noteContent = document.querySelector('.note-content');
      
//       const button = this;

//       summarizedElement.style.display = 'block';
//       noteContent.classList.remove('centered');
//       button.innerHTML = 'Regenerate ✨';
      
//     });
//   });


//------------------------------------------------------------------------------------------------

// document.addEventListener("DOMContentLoaded", function() {
//     var elements = document.querySelectorAll(".panel");
//     var win = window;
  
//     function visible(el, partial) {
//         var viewTop = win.scrollY;
//         var viewBottom = viewTop + win.innerHeight;
//         var top = el.getBoundingClientRect().top + win.scrollY;
//         var bottom = top + el.clientHeight;
//         var compareTop = partial ? bottom : top;
//         var compareBottom = partial ? top : bottom;
  
//         return ((compareBottom <= viewBottom) && (compareTop >= viewTop));
//     }
  
//     elements.forEach(function(el) {
//         if (visible(el, true)) {
//             el.classList.add("already-visible");
//         }
//     });
  
//     win.addEventListener("scroll", function() {
//         elements.forEach(function(el) {
//             if (visible(el, true)) {
//                 el.classList.add("come-in");
//             }
//         });
//     });

//     document.querySelector('.summarize').addEventListener('click', function(event) {
//         event.preventDefault();  // Prevent page reload

//         const summarizedElement = document.querySelector('.summaried-class');
//         const noteContent = document.querySelector('.note-content');
//         const button = this;

//         // Fetch the summarized text from the server
//         fetch('http://localhost:3000/summarize', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({ text: `Artificial Intelligence (AI) is a branch of computer science that focuses on creating intelligent machines capable of performing tasks that typically require human intelligence. These tasks include learning, reasoning, problem-solving, perception, language understanding, and more. The development and implementation of AI have profound implications for various sectors, including healthcare, education, business, and entertainment.
//             One of the most significant impacts of AI is in the healthcare industry. AI technologies are being used to improve diagnostic accuracy, personalize treatment plans, and streamline administrative tasks. For instance, AI algorithms can analyze medical images such as X-rays, MRIs, and CT scans to detect abnormalities with greater precision than human radiologists. In addition, AI-driven tools can sift through vast amounts of patient data to identify patterns and predict health outcomes, enabling proactive and preventive care. Personalized medicine is also benefiting from AI, as machine learning models can recommend tailored treatment plans based on an individual's genetic makeup, lifestyle, and medical history. Moreover, AI-powered chatbots and virtual assistants are enhancing patient engagement by providing 24/7 support and answering common health-related queries.
//             In the education sector, AI is transforming how students learn and teachers teach. Intelligent tutoring systems can provide personalized instruction and feedback, adapting to each student's learning pace and style. These systems can identify knowledge gaps and offer targeted practice exercises to reinforce understanding. AI-driven analytics can also help educators track student progress, identify at-risk students, and tailor interventions accordingly. Furthermore, AI-powered tools are making education more accessible to students with disabilities. For example, speech recognition software can transcribe lectures for hearing-impaired students, while text-to-speech applications can assist those with visual impairments.
//             Businesses are leveraging AI to enhance efficiency, productivity, and customer experiences. AI-powered chatbots and virtual assistants are handling customer inquiries, resolving issues, and providing recommendations around the clock. In the realm of data analysis, AI algorithms can process massive datasets to uncover insights, forecast trends, and drive strategic decision-making. Automation is another area where AI is making a significant impact. Robotic Process Automation (RPA) can automate repetitive, rule-based tasks, freeing employees to focus on more complex and creative work. Additionally, AI is being used in marketing to deliver personalized content and advertisements, improving customer engagement and conversion rates.
//             While the benefits of AI are undeniable, there are also ethical and societal concerns that need to be addressed. One of the primary concerns is the potential for job displacement. As AI automates more tasks, there is a risk that some jobs will become obsolete, leading to unemployment and economic inequality. To mitigate this, there is a need for policies and programs that support workforce reskilling and transition into new roles created by AI technologies.
//             Another concern is the ethical use of AI. There are instances where AI systems have exhibited biases, leading to unfair treatment or discrimination. This is often due to biased training data or flawed algorithms. To ensure fairness and accountability, it is crucial to implement robust mechanisms for auditing and regulating AI systems. Additionally, transparency in AI decision-making processes is essential to build trust and ensure ethical use.
//             Privacy is another critical issue. AI systems often require large amounts of data to function effectively. The collection, storage, and use of this data raise concerns about privacy and data security. It is important to establish strong data protection policies and practices to safeguard personal information and prevent misuse.
//             In conclusion, artificial intelligence has the potential to transform various aspects of society, from healthcare and education to business and entertainment. While the opportunities are immense, it is essential to address the ethical, societal, and privacy challenges associated with AI. By fostering responsible development and implementation, we can harness the power of AI to create a better future for all.` })
//         })
//         .then(response => response.json())
//         .then(data => {
//             document.getElementById('summary').innerText = data.summary;
//             summarizedElement.style.display = 'block';
//             noteContent.classList.remove('centered');
//             button.innerHTML = 'Regenerate ✨';
//         })
//         .catch(error => console.error('Error:', error));
//     });
// });

