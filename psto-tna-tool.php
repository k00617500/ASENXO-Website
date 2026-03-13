<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MSME Categorization · Likert 1‑4 | ASENXO</title>
  <link rel="icon" type="image/png" href="ASENXO-WEB/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
  <link rel="stylesheet" href="../src/css/psto-ben-stat-style.css">
  <link rel="stylesheet" href="../src/css/psto-tna-style.css">
  

</head>
<body class="dark">
<div class="app">
  <header class="top-header">
    <div class="top-header-left">
      <span class="project-name">ASENXO</span>
      <span class="badge">MSME CATEGORIZATION</span>
      <div class="search"><i class="fas fa-search"></i><input type="text" id="searchQuestion" placeholder="Search area / question..."></div>
      <div class="sort-filter-bar">
        <select id="objectiveFilter" style="display: none;">
          <option value="all">All objectives</option>
          <option value="1">1. Strategic direction</option>
          <option value="2">2. Management practices</option>
          <option value="3">3. Marketing practices</option>
          <option value="4">4. Technical practices</option>
          <option value="5">5. Energy & environment</option>
          <option value="6">6. Financial practices</option>
        </select>
        <span id="activeFilterBadge" class="filter-badge" style="display: none;"><span id="filterText"></span> <i class="fas fa-times-circle" id="clearFilterBtn"></i></span>
      </div>
    </div>
    <div class="top-header-right">
      <button class="btn-export" id="exportScores"><i class="fas fa-download"></i> Export</button>
      <button class="theme-toggle" id="themeToggle"><i class="fas fa-sun"></i> Light</button>
    </div>
  </header>

  <div class="content-row">
    <aside class="sidebar">
      <div class="sidebar-section">
        <div class="sidebar-header">MENU</div>
        <ul class="sidebar-menu">
          <li><a href="psto-home.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
          <li><a href="psto-ben-stat.php"><i class="fas fa-user-check"></i><span>Beneficiary Status</span></a></li>
          <li><a href="psto-map-view.php"><i class="fas fa-map-marked-alt"></i><span>Map View</span></a></li>
          <li class="active"><a href="psto-tna-tool.php"><i class="fas fa-clipboard-list"></i><span>TNA Tool</span></a></li>
          <li><a href="psto-revisions.php"><i class="fas fa-history"></i><span>Revisions</span></a></li>
          <li><a href="psto-endorsement.php"><i class="fas fa-check-circle"></i><span>Endorsement</span></a></li>
          <li><a href="psto-settings.php"><i class="fas fa-gear"></i><span>Settings</span></a></li>
        </ul>
      </div>
    </aside>

    <main class="main-content">
      <!-- Step-by-step layout -->
      <div class="tna-container">
        <!-- Left Sidebar - Vertical Steps Progress -->
        <div class="steps-sidebar">
          <div class="steps-header">
            <h3><i class="fas fa-tasks"></i> Assessment Progress</h3>
            <p>Complete all objectives to proceed</p>
          </div>
          
          <ul class="progress-steps" id="progressSteps">
            <!-- Steps will be populated by JavaScript -->
          </ul>
        </div>
        
        <!-- Main Content Area -->
        <div class="tna-main">
          <!-- Evaluator Info Card - Fixed spacing -->
          <div class="evaluator-card">
            <div class="evaluator-field">
              <label><i class="fas fa-user-circle"></i> EVALUATOR NAME</label>
              <input type="text" id="evaluatorName" placeholder="Enter evaluator name">
            </div>
            <div class="evaluator-field">
              <label><i class="fas fa-building"></i> ENTERPRISE</label>
              <select id="enterpriseSelect">
                <option value="">Select Enterprise</option>
                <option value="1" selected>Han Jim Marketing Corporation</option>
                <option value="2">4JNG Food Services</option>
                <option value="3">RMSS Garments Maker</option>
                <option value="4">SJL Corporation</option>
                <option value="5">Balay Sang Amo Food Products</option>
                <option value="6">Maravilla Enterprises Inc.</option>
                <option value="7">JLP Multi Ventures, Inc.</option>
                <option value="8">Belverim Foods Corporation</option>
                <option value="9">Orchard Valley, Inc.</option>
                <option value="10">Think About Cakes Bakery</option>
              </select>
            </div>
          </div>

          <!-- Current Objective Container -->
          <div id="currentObjectiveContainer"></div>

          <!-- Sub-page indicator (for questions 1-3, 4-6, etc.) -->
          <div id="subPageIndicator" class="sub-page-indicator"></div>

          <!-- Incomplete warning (hidden by default) -->
          <div id="incompleteWarning" class="incomplete-warning hidden">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Please answer all questions in this section before proceeding to the next.</span>
          </div>

          <!-- Questionnaire Container -->
          <div id="questionnaireContainer" class="questionnaire-grid">
            <div class="question-card" style="padding:40px; text-align:center;">Loading questionnaire...</div>
          </div>

          <!-- Navigation Buttons -->
          <div class="step-navigation">
            <div class="step-progress-text" id="stepProgressText">Objective 1 of 6</div>
            <div class="nav-buttons">
              <button class="btn-nav" id="prevBtn" disabled><i class="fas fa-arrow-left"></i> Previous</button>
              <button class="btn-nav" id="subPrevBtn" disabled><i class="fas fa-chevron-left"></i> Prev Question</button>
              <button class="btn-nav primary" id="subNextBtn">Next Question <i class="fas fa-chevron-right"></i></button>
              <button class="btn-nav primary" id="nextBtn">Next Objective <i class="fas fa-arrow-right"></i></button>
            </div>
          </div>

            <div id="resultsPanel" class="results-panel hidden">
            <div class="results-header">
                <h3><i class="fas fa-trophy"></i> Assessment Complete!</h3>
                <p>Based on your responses, here's the enterprise categorization</p>
            </div>
            
            <div class="results-grid">
                <div class="result-card">
                <div class="result-label">Answered</div>
                <div class="result-value" id="resultAnswered">0</div>
                <div>/55</div>
                </div>
                <div class="result-card">
                <div class="result-label">Average Score</div>
                <div class="result-value" id="resultAvg">0.00</div>
                </div>
                <div class="result-card">
                <div class="result-label">Category</div>
                <div style="font-size:24px; font-weight:600;" id="resultCategory">—</div>
                </div>
            </div>
            
            <div class="result-category" id="resultLevel">Level 1: Developing Enterprise</div>
            <div class="result-assistance" id="resultAssistance">STEP‑UP Assistance</div>
            
            <div class="results-actions">
                <button class="btn-outline" id="restartBtn"><i class="fas fa-redo-alt"></i> Start Over</button>
                <button class="btn-primary" id="exportResultsBtn"><i class="fas fa-download"></i> Export Report</button>
            </div>
            </div>
        </div>
      </div>
    </main>
  </div>
</div>

<script>
(function() {
  // The complete dataset from Excel
  const items = [
    // Objective 1 (6 items)
    { obj: "1", area: "Vision and Mission statements", question: "• How do you see the company five to ten years from now?\n• What do you think is the company's purpose?", scale1: "The company has no clear vision and mission statements.", scale2: "The company can state its vision and mission statements but it is not documented.", scale3: "The company has written vision and mission statements and it is communicated to employees.", scale4: "The company has written vision and mission statements which are reviewed regularly." },
    { obj: "1", area: "Business plan", question: "• What are your plans for the company? Are these written in a business plan?\n• How did you come up with your business plan?", scale1: "The company does not have a business plan.", scale2: "The company can communicate their business plan but it is not documented.", scale3: "The company has a documented business plan.", scale4: "The company has a documented business plan and is reviewed and updated regularly." },
    { obj: "1", area: "Business continuity and resiliency", question: "• How do you understand business continuity and resiliency?\n• How do you prepare your business for any crisis?", scale1: "The company has no idea of business continuity and resiliency.", scale2: "The company has knowledge of business continuity and resiliency.", scale3: "The company has a business continuity and resiliency plan but is not implemented.", scale4: "The company has a business continuity and resiliency plan and is implemented." },
    { obj: "1", area: "Acquisition and adoption of appropriate technologies", question: "• How do you acquire and adopt new technologies e.g processes, practices, and equipment for the company?\n• Are there any policies/procedures being followed?", scale1: "The company is not familiar with the concept of 'appropriate technology'.", scale2: "The company acquires or adopts technology without following an established procedure.", scale3: "The company implements a strategy to acquire or adopt appropriate technologies but no documented procedure is being followed.", scale4: "The company implements a strategy to acquire or adopt appropriate technologies which follow an established procedure for technology acquisition or adoption." },
    { obj: "1", area: "Partnerships and Collaborations", question: "• Do you have any memberships in any associations/groups? If none, why?\n• Do you have any formal or informal arrangements with other industry players? If yes, what are these agreements?", scale1: "The company is not a member of an association/group.", scale2: "The company is a member of an association/group related to the industry to which the company belongs.", scale3: "The company is a member of associations/groups related to the industry to which the company belongs and has collaborations with other industry players.", scale4: "The company holds a key position in the association which significantly benefits the business and has collaborations and legal partnerships with other industry players." },
    { obj: "1", area: "Digital transformation", question: "• Do you have any idea of digital transformation?\n• How do you store your data? -Analog (Written Manually, Record books, etc) -Digital Formats (MS Word, Excel, etc) -Information/Digital Systems", scale1: "The company has no knowledge on digital transformation, thus utilizes data on analog or manual formats.", scale2: "The company has gained knowledge of digital transformation and started implementing digitization through the conversion of data from analog formats to digital formats.", scale3: "The company has started to implement digitalization through establishment of information/digital systems.", scale4: "The company has embraced the knowledge of digital transformation through interconnectivity of information/digital systems and creating an interface between the systems." },
    
    // Objective 2 (11 items)
    { obj: "2", area: "Organizational Structure and Job Descriptions", question: "• Do you have an organizational structure? If yes, are the job descriptions specified?", scale1: "The company does have an organizational structure.", scale2: "There is an implied organizational structure that the owner verbally expressed.", scale3: "There is a written organizational structure but it does not include the job descriptions.", scale4: "There is a written organizational structure that includes well-defined job descriptions." },
    { obj: "2", area: "Recruitment and Selection", question: "• How do you recruit new employees for vacant positions in your company?\n• How do you select among the applicants?", scale1: "There are no established recruitment and selection processes.", scale2: "There are implied recruitment and selection processes that the owner verbally expressed.", scale3: "There are established recruitment and selection processes.", scale4: "There is are established recruitment and selection processes and it is reviewed and updated regularly." },
    { obj: "2", area: "Development and Training Programs", question: "• Do the employees attend training, workshops, seminars, etc.?\n• How do you choose the employee who can attend a certain training/workshop/seminar?", scale1: "There is no established development and training activities for employees.", scale2: "Development and training activities are implemented as needed.", scale3: "There is an established development and training program.", scale4: "There is an established development and training program and it is reviewed and updated regularly." },
    { obj: "2", area: "Performance Evaluation System and Rewards and Recognitions", question: "• How do you evaluate the performance of your employees?\n• How do you communicate the results of the evaluation to your employees?\n• Do you provide incentives/recognitions for employees who excel?", scale1: "Employee performance evaluation and rewards and recognition are not evident.", scale2: "There are implied employee performance evaluations and rewards and recognitions that the owner verbally expressed.", scale3: "There are established procedures for employee performance evaluations and rewards and recognitions.", scale4: "There are established procedures for employee performance evaluations and rewards and recognitions and it is reviewed and updated regularly." },
    { obj: "2", area: "Knowledge and Skills of Employees", question: "• How do you describe the level of knowledge and skills of your employees?\n• Did they undergo training for their specific functions in the company?\n• Do you have a training plan prepared for your employees?", scale1: "Employees have the basic knowledge and skills necessary for their jobs.", scale2: "Employees are equipped with adequate knowledge and skills necessary for their jobs so training is not necessary at this time.", scale3: "Employees are equipped with adequate knowledge and skills necessary for their jobs. The company acknowledges that training is still necessary but a training plan is not evident.", scale4: "Employees are equipped with all the essential knowledge and skills necessary for them to perform different jobs in the company. The company has prepared a training plan for their employees." },
    { obj: "2", area: "Management's Technology Awareness", question: "• Do you have any idea of the latest technologies available in the industry?\n• Do you have any plans to adopt/apply these technologies in your operations?", scale1: "Management is not familiar of the latest technologies available in the industry.", scale2: "Management is aware of the latest technologies available in the industry.", scale3: "Management is fully aware and informed of the latest technologies available in the industry.", scale4: "Management takes action and develops initiative to apply the latest technologies available in the industry." },
    { obj: "2", area: "Occupational Health and Safety Management", question: "• How do you practice occupational health and safety?\n• (can also refer to the checklist for OHS)", scale1: "Occupational health and safety practices are not evident.", scale2: "Occupational and safety management is practiced but it is not documented.", scale3: "Occupational and safety management is practiced and documented.", scale4: "Occupational and safety management is documented, reviewed, and updated regularly." },
    { obj: "2", area: "Business Ethics and Social Responsibility", question: "• How do you manage your social obligations those involved in your business (employees, customers, suppliers, etc.)\n• Does your operation affect the environment? How about the community around you?\n• How do you mitigate the adverse effects of your operations to the environment and the community?", scale1: "The company is not aware of the impact of its operations on the environment and society.", scale2: "The company recognizes the impact of its operations on the environment and society.", scale3: "The company acts responsibly against the impact of its operations on the environment and society.", scale4: "The company has established a well-defined policy illustrating its relationship to society, immediate stakeholders, and the community." },
    { obj: "2", area: "Purchasing System", question: "• How do you purchase the resources needed by the company?", scale1: "A purchasing system is not evident.", scale2: "There is a purchasing system being practiced but not documented.", scale3: "There is a well-established and documented purchasing system.", scale4: "There is a well-established and documented purchasing system that is reviewed, and updated regularly." },
    { obj: "2", area: "ICT Management", question: "• How do you advertise/promote your products/services? Do you utilize any online platforms?\n• How do you maintain any online presence?\n• How do you gather information regarding your business operations?", scale1: "The company uses a smart phone and mobile internet for simple product marketing and customer relations (social media, instant messaging, email).", scale2: "The company uses a computer and the internet to establish and maintain an online presence on social media, and/or marketing platforms and to gather the information that can aid in decision making", scale3: "The company uses a computer and the internet and its own website to maintain an online presence on social media, and marketing platforms and to be kept updated about industry developments to aid in decision making.", scale4: "The company has well maintained online presence, and established information systems, applications and databases to manage the enterprise like inventory, sales, reports, customer and supplier information, which are used in decision making." },
    { obj: "2", area: "Public Media Relations", question: "• What are the activities conducted that can ensure the development and maintenance of the company's public image?", scale1: "The company does not have a public and media relations plan.", scale2: "The company implements public and media relations plan but is not documented.", scale3: "The company implements a documented public and media relations plan.", scale4: "The company has a well-designed public and media relations plan and is regularly evaluated and updated." },
    
    // Objective 3 (7 items)
    { obj: "3", area: "Product/Services Positioning", question: "• How do you position your products/services in the market?\n• How is it compared to your competitors?", scale1: "Product/service positioning is not defined.", scale2: "Product/service positioning is defined but not documented.", scale3: "Product/service positioning is defined and documented.", scale4: "Product/service positioning is defined, documented, reviewed, and updated regularly." },
    { obj: "3", area: "Pricing Policy", question: "• How do you set the prices for your products/services?\n• Do you have any basis for these prices?", scale1: "The company does not follow a defined pricing strategy.", scale2: "The company follows a defined pricing strategy but is not documented.", scale3: "The company follows a defined pricing strategy and is documented.", scale4: "The company follows a defined pricing strategy and is documented, reviewed, and adjusted accordingly." },
    { obj: "3", area: "Distribution Channels", question: "• How do you distribute your products?\n• Do you sell it in retail, wholesale, or directly to the consumer?", scale1: "Distribution channels are not defined.", scale2: "Distribution channels are defined but not documented.", scale3: "Distribution channels are defined and documented.", scale4: "Distribution channels are defined, documented, reviewed, and updated." },
    { obj: "3", area: "Marketing Plan", question: "• Do you have a marketing plan? If yes, is it written?\n• How do you advertise your products/services?", scale1: "The company does not have a marketing plan.", scale2: "The owner verbally expressed the company's marketing plan.", scale3: "The company has a documented marketing plan.", scale4: "The company has a documented marketing plan that is continuously reviewed, and updated." },
    { obj: "3", area: "Customer Satisfaction", question: "• Do you gather feedback from your customer? If yes, how do you conduct this?", scale1: "The company does not gather customer satisfaction feedback for the improvement of its products/services.", scale2: "The company informally gathers customer satisfaction feedback for the improvement of its products/services.", scale3: "The company has an established procedure to determine customer satisfaction feedback for the improvement of its products/services.", scale4: "The company has an established procedure to determine customer satisfaction feedback and the results are analyzed to improve the products/services." },
    { obj: "3", area: "E-Commerce, Digital Marketing & Analytics", question: "• Do you have social media accounts, or your own website? Do you also avail of e-commerce platforms like Shopee & Lazada?\n• How do you utilize these to benefit your company?", scale1: "The company is not utilizing any social media (e.g. Facebook, Instagram, and Twitter) or e-commerce platforms (e.g. Lazada, Shopee, and Alibaba) to market its products/services.", scale2: "The company is utilizing the social media (such as Facebook, Instagram, and Twitter) platforms to market its products/services.", scale3: "The company is utilizing the social media (such as Facebook, Instagram, and Twitter) and e-commerce platforms (such as Lazada, Shopee, and Alibaba) to market its products/services.", scale4: "The company is utilizing its own website, social media, and e-commerce platforms and is analyzing market performance through e-commerce analytics." },
    { obj: "3", area: "Product/Service Lifecycle", question: "• Are you aware of the product lifecycle?\n• If yes, do you monitor the stage where your product is right now? How?", scale1: "The company has no idea of the product/service lifecycle.", scale2: "The company has knowledge on the product/service lifecycle.", scale3: "The company monitors the product/service lifecycle but is not utilized for the improvement of market plans and strategies.", scale4: "The company monitors the product/service lifecycle and is utilized and analyzed for the improvement of market plans and strategies." },
    
    // Objective 4 (16 items)
    { obj: "4", area: "Product Characterization", question: "• How do you define your products/services? Have you subjected your products to laboratory analysis?", scale1: "Product characteristics are not defined.", scale2: "Product characteristics are defined but not documented.", scale3: "Product characteristics are defined and documented.", scale4: "Product characteristics are defined and documented and have met applicable commodity specification standards." },
    { obj: "4", area: "Raw Materials", question: "• How do you choose your raw materials? Do you have set criteria?", scale1: "The acceptance criteria for the firm's raw materials are not defined.", scale2: "The acceptance criteria for the firm's raw materials are defined but not documented.", scale3: "The acceptance criteria for the firm's raw materials are defined and documented.", scale4: "The acceptance criteria for the firm's raw materials are defined, documented, reviewed, and updated." },
    { obj: "4", area: "Production System", question: "• What are the processes involved in your production?\n• Do you monitor these processes?", scale1: "The company has no defined production system.", scale2: "The company has identified the processes involved in the production but it is not documented.", scale3: "The company has an established production system with well-defined and documented process requirements.", scale4: "The company has an established production system with well-defined, documented and monitored process requirements." },
    { obj: "4", area: "Zoning and Hazard Assessment", question: "• Do you own this place or rented? If rented, do you have a contract with the landowner?\n• What is the current land use or zoning of the place where your plant is built in?\n• Has the place undergone any form of hazard assessment? Assessed through HazardHunterPH?", scale1: "The plant site is temporary and does not conform with the approved land use or zoning.", scale2: "The plant site is owned/rented (with contract) by the company but does not conform with the approved land use or zoning.", scale3: "The plant site is owned/rented (with contract) by the company, conforms with the approved land use or zoning, and has undergone hazard assessment. However, there is no room for expansion.", scale4: "The plant site is owned/rented (with contract) by the company, conforms with the approved land use or zoning, and has undergone hazard assessment. Also, there is room for expansion." },
    { obj: "4", area: "Production Lay-Out", question: "• Do you have a production lay-out? If yes, have you observed crisscrossing of movements?", scale1: "The owner verbally expressed the company's production lay-out.", scale2: "There is a documented production layout but the owner expressed that he/she had observed crisscrossing of movements.", scale3: "There is a documented production layout and it is systematically arranged based on the owner's observation.", scale4: "The production layout is systematically arranged for a smooth production flow, well-documented, and regularly updated." },
    { obj: "4", area: "Outsourcing Practices", question: "• Do you practice outsourcing? How do you conduct this?", scale1: "Outsourcing is not being practiced.", scale2: "Outsourcing is practiced but no formal agreement with a service provider.", scale3: "The company has an outsourcing agreement with a service provider.", scale4: "The company has an outsourcing agreement with a service provider and is reviewed and updated." },
    { obj: "4", area: "Technology Upgrading", question: "• How many years have you been using your current processes or equipment?\n• Do you plan on upgrading your current technology?", scale1: "Technology upgrading is a priority but not planned.", scale2: "Technology upgrading is planned but not implemented.", scale3: "Technology upgrading is planned and implemented.", scale4: "Technology upgrading is planned, implemented, and reviewed." },
    { obj: "4", area: "Digital Infrastructures", question: "• Do you use electronic devices and internet for your business operations?", scale1: "The company uses a smart phone and mobile internet.", scale2: "The company has established a small office/home office network with dedicated internet connectivity.", scale3: "The company has established a network, internet connectivity, and cloud service subscription.", scale4: "The company has established a network, cloud service subscription, and on-premise ICT equipment (servers, IoT devices, etc)." },
    { obj: "4", area: "Basic Automation", question: "• Is automation part of your production processes? If yes, in what part of the process?", scale1: "Basic automation is non-existent.", scale2: "There is a plan to automate simple and rudimentary tasks.", scale3: "Automation of simple and rudimentary tasks is existent but inadequate.", scale4: "Automation of simple and rudimentary tasks is adequate." },
    { obj: "4", area: "Operations and maintenance", question: "• How do you maintain your equipment? Structures? Facilities?", scale1: "Maintenance program is not evident.", scale2: "Maintenance program is not regularly followed.", scale3: "Maintenance program is established and implemented.", scale4: "Maintenance program is established, implemented, reviewed, and updated." },
    { obj: "4", area: "Research and Development", question: "• How do you develop your products or services?\n• Do you have a program for your research and development?", scale1: "R&D is through trial and error method.", scale2: "R&D is planned for the development/improvement of products/services but not documented.", scale3: "R&D is planned for the development/improvement of products/services and is documented.", scale4: "The company has developed partnerships and linkages with the R&D community to further develop/improve its products/services." },
    { obj: "4", area: "Performance Measures or Key Performance Indicators", question: "• Do you inspect or test the inputs and outputs in your operations?\n• How do you monitor and record the needed parameters on each stage of the process?\n• What quality standards do you follow for you raw materials and products?", scale1: "Not all identified stages in production have performance indicators.", scale2: "Each identified stages in production have performance indicators but there is no well-established system to measure performance vis-à-vis indicators.", scale3: "Each identified stages in production have performance indicators and a well-established system to measure performance vis-à-vis indicators but is not used to support data-driven decision making.", scale4: "Each identified stages in production have performance indicators and a well-established system to measure performance vis-à-vis indicators to support data-driven decision-making." },
    { obj: "4", area: "Quality Assurance", question: "• How to you measure the quality of your products/services?\n• Is there an existing documented quality assurance system for implementation?", scale1: "The company has no defined quality assurance system.", scale2: "The company implements quality assurance system but it is not documented.", scale3: "The company has an established quality assurance system with well-defined and documented process requirements.", scale4: "The company has an established quality assurance system with well-defined, documented and monitored process requirements." },
    { obj: "4", area: "Data Analytics", question: "• How do you collect data needed for your operations?\n• How do you analyze it?", scale1: "No data collected and available for use.", scale2: "Only a few data collected.", scale3: "Data is collected, however not utilized and analyzed.", scale4: "Data is fully collected, analyzed, and utilized." },
    { obj: "4", area: "IT Systems", question: "• Are you aware of IT Systems?\n• Do you utilize any IT Systems in any part of your operation? What are these?", scale1: "The company is not aware of the IT Systems.", scale2: "The company recognizes the support of IT Systems but does not implement.", scale3: "Some areas of the processes are supported by IT Systems", scale4: "Operations is fully supported by IT Systems." },
    { obj: "4", area: "Data Protection", question: "• How do you protect the data of the company?\n• Do you have any protection policies for your data?", scale1: "The company is not aware of data protection.", scale2: "The company has no data protection policies in place.", scale3: "The company has good understanding of the policies but protection regulation is not updated.", scale4: "The company updates data protection policy which complies with necessary standards." },
    
    // Objective 5 (6 items)
    { obj: "5", area: "Environmental and Sustainability Management", question: "• How do you manage the environmental effect of the company's operation?\n• Have you applied for or currently have an Environmental Compliance Certificate (ECC) or Certificate of Non-Coverage (CNC)?", scale1: "The company is not compliant with environmental regulations.", scale2: "The company is compliant with environmental regulations but without proof of certification.", scale3: "The company is compliant with environmental regulations with proof of certification.", scale4: "The company implements applicable environmental management systems." },
    { obj: "5", area: "Energy Conservation", question: "• Do you monitor your energy consumption for your operations?\n• What practices do you conduct to reduce your energy consumption?", scale1: "The company is aware of its energy consumption for the whole operations.", scale2: "The company monitors energy consumption for the whole operation but is not documented.", scale3: "The company monitors energy consumption for the whole operation and is properly documented.", scale4: "The company implements applicable energy management systems." },
    { obj: "5", area: "Sustainability of Raw materials", question: "• How do you purchase your raw materials? Do you have standards set?\n• Who are your suppliers? Do you have a contract with your suppliers?\n• Are these raw materials sustainable?", scale1: "The company purchases raw materials that are available in the market.", scale2: "The company has suppliers but is not assured of the sustainability of raw materials.", scale3: "The company has suppliers and has set standards on the sustainability of the raw materials.", scale4: "The company has accredited suppliers and has established standards on the sustainability of the raw materials." },
    { obj: "5", area: "Waste Management", question: "• How do you dispose of your wastes?\n• Do you practice waste segregation, reduction, reusing and recycling?", scale1: "The company is not practicing waste segregation.", scale2: "The company practices waste segregation.", scale3: "The company practices waste segregation and the 3Rs namely reduce, reuse, and recycle.", scale4: "The company practices waste segregation and the 3Rs namely reduce, reuse, and recycle and have produced by-products from its wastes." },
    { obj: "5", area: "Green Technology", question: "• Do you have any idea on green technology?\n• Have you implemented any green technology? What are these?", scale1: "The company has no idea of green technology.", scale2: "The company has knowledge of green technology but does not implement it.", scale3: "The company implements green technology e.g.solar panel, rainwater harvester, low cost or electricity efficient equipment.", scale4: "The company has a green technology plan and is implemented." },
    { obj: "5", area: "Disaster and Hazard Preparedness", question: "• How do the company prepare in times of disaster/hazard?\n• Do you have any continuity plans in preparation for any disaster or hazard occurrence?", scale1: "The company is reactive in terms of disaster and hazard occurrence.", scale2: "The company implements disaster and hazard preparedness but is not documented.", scale3: "The company has a documented continuity plans in case of disaster and hazard occurrence.", scale4: "The company has well-established and well-documented continuity plans in case of disaster and hazard occurrence and is continually evaluated and updated." },
    
    // Objective 6 (9 items)
    { obj: "6", area: "Financial Approach", question: "• How do you manage your personal and business finances?", scale1: "Personal and business finances are not separated.", scale2: "There is an understanding to separate personal and business finances but not yet implemented.", scale3: "Personal and business finances are separated.", scale4: "Maintains exclusive bank accounts for personal and business finances." },
    { obj: "6", area: "Financial management practices as to recording", question: "• What are the records you keep and maintain e.g invoices, receipts and subsidiary ledgers?", scale1: "The company keeps track of and records its daily sales every day.", scale2: "The company records all its expenses incurred in the operation of the business.", scale3: "The company records all its inventory/or stock purchases in the ledger.", scale4: "The company keeps track of all its collectibles/receivables in the book of accounts." },
    { obj: "6", area: "Financial management practices as to budgeting", question: "• What budgets do you prepare e.g. budget for products/raw materials to be produced only or including the budget for electricity, taxes, and others?", scale1: "The company makes a budget schedule for all the goods that it produces or sells.", scale2: "The company makes a budgetary allocation for all the expenses that it incurs in running the business.", scale3: "The company makes forecasts on the future demand and supply of the goods that it produces or sells.", scale4: "The company makes projections on the future operating expenses of its business." },
    { obj: "6", area: "Financial management practices as to reporting", question: "• How do you prepare your financial statements? How often?\n• Do you have your own bookkeeper?", scale1: "Financial statements are prepared if necessary by the management.", scale2: "Financial statements are prepared yearly by an outsourced bookkeeper.", scale3: "Financial statements are prepared yearly by the company's hired bookkeeper.", scale4: "Financial statements are prepared yearly by the company's accountant and audited by an independent auditor." },
    { obj: "6", area: "Financial management practices as to inventory management", question: "• How do you order and store your products or raw materials?\n• Do you practice a first-in, first-out inventory system?", scale1: "The company conducts an inventory or a physical counting of stocks regularly.", scale2: "The company practices the first-in and first-out inventory management system.", scale3: "The company projects the required level of inventory of stocks needed at a regularly specified time.", scale4: "The company uses/applies technology in recording the ins and outs of their stocks like Point of Sales System (POS), etc." },
    { obj: "6", area: "Financial capacity to take financial risks to adopt technologies", question: "• Are you willing to adopt new technologies at your own expense?\n• Do you have the financial capacity to adopt new technologies?", scale1: "The company has no financial capacity to adopt technologies.", scale2: "The company has financial capacity but needs assistance to take financial risks to adopt off-the-shelf technologies.", scale3: "The company has financial capacity but needs assistance to take financial risks to adopt technologies ready for commercialization from the country's RDIs.", scale4: "The company has financial capacity but needs assistance to take financial risks to adopt state-of-the-art technologies from other countries." },
    { obj: "6", area: "Financial capacity to avail and implement consultancy programs and R&D initiatives", question: "• Have you hired a consultant at your own expense?", scale1: "The company only avails of free consultancy services.", scale2: "The company only avails of free consultancy services and is capable to implement short-term recommendations.", scale3: "The company has the financial capacity to hire a consultant but needs technical assistance on R&D.", scale4: "The company has the financial capacity to hire consultants and perform in-house R&D." },
    { obj: "6", area: "Labor productivity", question: "• Do you compute labor productivity? If yes, what is the company's labor productivity?", scale1: "The company has no idea of its labor productivity.", scale2: "The company computes its labor productivity but it is not recorded.", scale3: "The company computes its labor productivity and is recorded.", scale4: "The company consistently computes, monitors, and reviews its labor productivity." },
    { obj: "6", area: "Capital Productivity", question: "• Do you compute capital productivity? If yes, what is the company's capital productivity?", scale1: "The company has no idea of its capital productivity.", scale2: "The company computes its capital productivity but it is not recorded.", scale3: "The company computes its capital productivity and is recorded.", scale4: "The company consistently computes, monitors, and reviews its capital productivity." }
  ];

  console.log('Loaded', items.length, 'items');

  // Group items by objective
  const objectives = {};
  items.forEach(item => {
    if (!objectives[item.obj]) {
      objectives[item.obj] = [];
    }
    objectives[item.obj].push(item);
  });

  const objectiveList = Object.keys(objectives).sort();
  const totalObjectives = objectiveList.length;

  // State
  let scores = new Array(items.length).fill(null);
  let currentObjectiveIndex = 0;
  let currentSubPage = 0; // 0-based index for groups of 3 questions
  let evaluatorName = 'Juan Dela Cruz';
  let selectedEnterprise = '1';

  // DOM elements
  const container = document.getElementById('questionnaireContainer');
  const progressStepsEl = document.getElementById('progressSteps');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const subPrevBtn = document.getElementById('subPrevBtn');
  const subNextBtn = document.getElementById('subNextBtn');
  const stepProgressText = document.getElementById('stepProgressText');
  const subPageIndicator = document.getElementById('subPageIndicator');
  const incompleteWarning = document.getElementById('incompleteWarning');
  const evaluatorNameInput = document.getElementById('evaluatorName');
  const enterpriseSelect = document.getElementById('enterpriseSelect');
  const resultsPanel = document.getElementById('resultsPanel');
  const currentObjectiveContainer = document.getElementById('currentObjectiveContainer');

  function getObjName(objVal) {
    const map = {
      '1': 'Strategic direction',
      '2': 'Management practices',
      '3': 'Marketing practices',
      '4': 'Technical practices',
      '5': 'Energy & environment',
      '6': 'Financial practices'
    };
    return map[objVal] || '';
  }

  function getQuestionsForObjective(obj) {
    return objectives[obj] || [];
  }

  function getCurrentObjectiveQuestions() {
    return getQuestionsForObjective(objectiveList[currentObjectiveIndex]);
  }

  function getTotalSubPages() {
    const questions = getCurrentObjectiveQuestions();
    return Math.ceil(questions.length / 3);
  }

  function getCurrentSubPageQuestions() {
    const questions = getCurrentObjectiveQuestions();
    const startIdx = currentSubPage * 3;
    return questions.slice(startIdx, startIdx + 3);
  }

  function areAllCurrentQuestionsAnswered() {
    const currentQuestions = getCurrentSubPageQuestions();
    return currentQuestions.every(q => scores[items.indexOf(q)] !== null);
  }

  function renderProgressSteps() {
    let stepsHtml = '';
    objectiveList.forEach((obj, index) => {
      const objQuestions = getQuestionsForObjective(obj);
      const answeredInObj = objQuestions.filter(q => scores[items.indexOf(q)] !== null).length;
      const totalInObj = objQuestions.length;
      const isCompleted = answeredInObj === totalInObj && totalInObj > 0;
      const isActive = index === currentObjectiveIndex;
      const completionPercent = Math.round((answeredInObj / totalInObj) * 100) || 0;
      
      stepsHtml += `
        <li class="progress-step ${isActive ? 'active' : ''} ${isCompleted ? 'completed' : ''}" data-obj-index="${index}">
          <span class="step-indicator">${isCompleted ? '<i class="fas fa-check"></i>' : index + 1}</span>
          <div class="step-content">
            <div class="step-title">Objective ${obj}</div>
            <div class="step-subtitle">${getObjName(obj)}</div>
            <div class="step-status">${answeredInObj}/${totalInObj} answered (${completionPercent}%)</div>
            ${!isCompleted && isActive && answeredInObj < totalInObj ? 
              '<div class="step-warning"><i class="fas fa-exclamation-circle"></i> Incomplete</div>' : ''}
          </div>
        </li>
      `;
    });
    progressStepsEl.innerHTML = stepsHtml;

    // Add click handlers - but only allow clicking on completed or current
    document.querySelectorAll('.progress-step').forEach(step => {
      step.addEventListener('click', function() {
        const objIndex = parseInt(this.dataset.objIndex);
        if (!isNaN(objIndex)) {
          // Check if we can navigate to this objective
          const targetObj = objectiveList[objIndex];
          const targetQuestions = getQuestionsForObjective(targetObj);
          const answeredTarget = targetQuestions.filter(q => scores[items.indexOf(q)] !== null).length;
          
          // Allow if it's completed or it's the current or previous objectives
          if (objIndex <= currentObjectiveIndex || answeredTarget === targetQuestions.length) {
            currentObjectiveIndex = objIndex;
            currentSubPage = 0;
            renderCurrentObjective();
          } else {
            // Show warning
            alert('Please complete the current objective first before jumping ahead.');
          }
        }
      });
    });
  }

  function renderSubPageIndicator() {
    const totalSubPages = getTotalSubPages();
    if (totalSubPages <= 1) {
      subPageIndicator.classList.add('hidden');
      return;
    }
    
    subPageIndicator.classList.remove('hidden');
    
    const currentObj = objectiveList[currentObjectiveIndex];
    const objName = getObjName(currentObj);
    const startQ = currentSubPage * 3 + 1;
    const endQ = Math.min((currentSubPage + 1) * 3, getCurrentObjectiveQuestions().length);
    
    let dotsHtml = '';
    for (let i = 0; i < totalSubPages; i++) {
      const isActive = i === currentSubPage;
      const isCompleted = (i < currentSubPage) || 
        (i === currentSubPage && areAllCurrentQuestionsAnswered());
      dotsHtml += `<span class="sub-page-dot ${isActive ? 'active' : ''} ${isCompleted ? 'completed' : ''}"></span>`;
    }
    
    subPageIndicator.innerHTML = `
      <div class="sub-page-text">
        <i class="fas fa-layer-group"></i> ${objName} - Questions ${startQ}-${endQ}
      </div>
      <div class="sub-page-dots">
        ${dotsHtml}
      </div>
    `;
  }

  function renderCurrentObjective() {
    const currentObj = objectiveList[currentObjectiveIndex];
    const questions = getCurrentSubPageQuestions();
    const totalSubPages = getTotalSubPages();
    
    // Update step progress text
    stepProgressText.textContent = `Objective ${currentObj} of ${totalObjectives} - ${getObjName(currentObj)} (Page ${currentSubPage + 1}/${totalSubPages})`;

    // Update active state in progress steps
    document.querySelectorAll('.progress-step').forEach((step, idx) => {
      if (idx === currentObjectiveIndex) {
        step.classList.add('active');
      } else {
        step.classList.remove('active');
      }
    });

    // Render sub-page indicator
    renderSubPageIndicator();

    // Hide warning initially
    incompleteWarning.classList.add('hidden');

    // Render questions for current sub-page
    let html = '';
    let cardIndex = 0;
    
    questions.forEach((item) => {
      const globalIdx = items.indexOf(item);
      const selected = scores[globalIdx];
      const radios = [1, 2, 3, 4].map(v => 
        `<label><input type="radio" name="q${globalIdx}" value="${v}" ${selected === v ? 'checked' : ''}> ${v}</label>`
      ).join('');
      
      const safeQuestion = item.question.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>');
      const safeScale1 = item.scale1.replace(/</g, '&lt;').replace(/>/g, '&gt;');
      const safeScale2 = item.scale2.replace(/</g, '&lt;').replace(/>/g, '&gt;');
      const safeScale3 = item.scale3.replace(/</g, '&lt;').replace(/>/g, '&gt;');
      const safeScale4 = item.scale4.replace(/</g, '&lt;').replace(/>/g, '&gt;');
      
      html += `
        <div class="question-card" data-index="${globalIdx}" style="--card-index: ${cardIndex}">
          <div class="question-left">
            <span class="area-badge">${item.area}</span>
            <div class="question-text">${safeQuestion}</div>
            <div class="likert-row">
              <div class="likert-options">${radios}</div>
            </div>
          </div>
          <div class="scale-reference">
            <p><i class="fas fa-info-circle"></i> Scale meaning</p>
            <ul>
              <li>1 – ${safeScale1}</li>
              <li>2 – ${safeScale2}</li>
              <li>3 – ${safeScale3}</li>
              <li>4 – ${safeScale4}</li>
            </ul>
          </div>
        </div>
      `;
      cardIndex++;
    });

    container.innerHTML = html;

    // Attach change listeners
    document.querySelectorAll('.question-card').forEach(card => {
      const idx = card.dataset.index;
      card.querySelectorAll('input[type="radio"]').forEach(r => {
        r.addEventListener('change', function() {
          if (this.checked) {
            scores[idx] = parseInt(this.value, 10);
            updateProgressSteps();
            updateNavButtons();
            
            // Hide warning if all are now answered
            if (areAllCurrentQuestionsAnswered()) {
              incompleteWarning.classList.add('hidden');
            }
          }
        });
      });
    });

    updateNavButtons();
  }

  function updateProgressSteps() {
    objectiveList.forEach((obj, index) => {
      const objQuestions = getQuestionsForObjective(obj);
      const answeredInObj = objQuestions.filter(q => scores[items.indexOf(q)] !== null).length;
      const totalInObj = objQuestions.length;
      const isCompleted = answeredInObj === totalInObj && totalInObj > 0;
      const completionPercent = Math.round((answeredInObj / totalInObj) * 100) || 0;
      
      const stepEl = document.querySelector(`.progress-step[data-obj-index="${index}"]`);
      if (stepEl) {
        if (isCompleted) {
          stepEl.classList.add('completed');
          const indicator = stepEl.querySelector('.step-indicator');
          if (indicator) {
            indicator.innerHTML = '<i class="fas fa-check"></i>';
          }
        } else {
          stepEl.classList.remove('completed');
          const indicator = stepEl.querySelector('.step-indicator');
          if (indicator) {
            indicator.textContent = index + 1;
          }
        }
        
        const statusEl = stepEl.querySelector('.step-status');
        if (statusEl) {
          statusEl.textContent = `${answeredInObj}/${totalInObj} answered (${completionPercent}%)`;
        }
        
        // Update warning
        const warningEl = stepEl.querySelector('.step-warning');
        if (!isCompleted && index === currentObjectiveIndex && answeredInObj < totalInObj) {
          if (!warningEl) {
            const newWarning = document.createElement('div');
            newWarning.className = 'step-warning';
            newWarning.innerHTML = '<i class="fas fa-exclamation-circle"></i> Incomplete';
            stepEl.querySelector('.step-content').appendChild(newWarning);
          }
        } else if (warningEl) {
          warningEl.remove();
        }
      }
    });
    
    // Also update sub-page indicator dots
    renderSubPageIndicator();
  }

  function updateNavButtons() {
    const totalSubPages = getTotalSubPages();
    const allAnswered = areAllCurrentQuestionsAnswered();
    
    // Previous objective button
    prevBtn.disabled = currentObjectiveIndex === 0;
    
    // Sub-page navigation
    subPrevBtn.disabled = currentSubPage === 0;
    subNextBtn.disabled = currentSubPage === totalSubPages - 1;
    
    // Next objective button - only enable if all questions in current objective are answered
    const currentObjQuestions = getCurrentObjectiveQuestions();
    const allCurrentObjAnswered = currentObjQuestions.every(q => scores[items.indexOf(q)] !== null);
    
    if (currentObjectiveIndex === totalObjectives - 1 && allCurrentObjAnswered) {
      nextBtn.innerHTML = 'Finish <i class="fas fa-check-circle"></i>';
      nextBtn.disabled = false;
    } else if (currentObjectiveIndex < totalObjectives - 1) {
      nextBtn.innerHTML = 'Next Objective <i class="fas fa-arrow-right"></i>';
      nextBtn.disabled = !allCurrentObjAnswered;
    } else {
      nextBtn.disabled = true;
    }
    
    // Show warning if trying to proceed without answering
    if (!allAnswered && (subNextBtn.disabled === false)) {
      // They can still navigate but we'll show warning
    }
  }

  function showResults() {
    // Hide questionnaire and nav, show results
    container.classList.add('hidden');
    subPageIndicator.classList.add('hidden');
    document.querySelector('.step-navigation').classList.add('hidden');
    incompleteWarning.classList.add('hidden');
    resultsPanel.classList.remove('hidden');
    
    // Calculate totals
    const answered = scores.filter(v => v !== null).length;
    const total = scores.length;
    const sum = scores.reduce((acc, v) => acc + (v || 0), 0);
    const avg = answered ? (sum / answered) : 0;
    
    // Update results
    document.getElementById('resultAnswered').textContent = answered;
    document.getElementById('resultAvg').textContent = avg.toFixed(2);
    
    let level = '';
    let category = '';
    let assistance = '';
    
    if (avg >= 3.01) { 
      level = 'Level 3: Expanding and Innovating';
      category = 'Expanding';
      assistance = 'SURGE‑UP Assistance'; 
    } else if (avg >= 2.01) { 
      level = 'Level 2: Growing Enterprise';
      category = 'Growing';
      assistance = 'SCALE‑UP Assistance'; 
    } else if (avg >= 1.0) { 
      level = 'Level 1: Developing Enterprise';
      category = 'Developing';
      assistance = 'STEP‑UP Assistance'; 
    } else { 
      level = '—';
      category = '—';
      assistance = 'STEP‑UP Assistance'; 
    }
    
    document.getElementById('resultLevel').textContent = level;
    document.getElementById('resultCategory').textContent = category;
    document.getElementById('resultAssistance').textContent = assistance;
  }

  // Navigation handlers
  prevBtn.addEventListener('click', () => {
    if (currentObjectiveIndex > 0) {
      currentObjectiveIndex--;
      currentSubPage = 0;
      renderCurrentObjective();
    }
  });

  subPrevBtn.addEventListener('click', () => {
    if (currentSubPage > 0) {
      currentSubPage--;
      renderCurrentObjective();
    }
  });

  subNextBtn.addEventListener('click', () => {
    const totalSubPages = getTotalSubPages();
    
    // Check if current page questions are answered
    if (!areAllCurrentQuestionsAnswered()) {
      incompleteWarning.classList.remove('hidden');
      return;
    }
    
    if (currentSubPage < totalSubPages - 1) {
      currentSubPage++;
      renderCurrentObjective();
    }
  });

  nextBtn.addEventListener('click', () => {
    // Check if all questions in current objective are answered
    const currentObjQuestions = getCurrentObjectiveQuestions();
    const allAnswered = currentObjQuestions.every(q => scores[items.indexOf(q)] !== null);
    
    if (!allAnswered) {
      incompleteWarning.classList.remove('hidden');
      incompleteWarning.innerHTML = `
        <i class="fas fa-exclamation-triangle"></i>
        <span>Please answer all questions in this objective before proceeding to the next.</span>
      `;
      return;
    }
    
    // Check if on last objective
    if (currentObjectiveIndex === totalObjectives - 1) {
      // Show results
      showResults();
    } else {
      // Move to next objective
      currentObjectiveIndex++;
      currentSubPage = 0;
      renderCurrentObjective();
    }
  });

  // Restart button
  document.getElementById('restartBtn').addEventListener('click', () => {
    scores = new Array(items.length).fill(null);
    currentObjectiveIndex = 0;
    currentSubPage = 0;
    container.classList.remove('hidden');
    document.querySelector('.step-navigation').classList.remove('hidden');
    resultsPanel.classList.add('hidden');
    renderProgressSteps();
    renderCurrentObjective();
  });

  // Export results
  document.getElementById('exportResultsBtn').addEventListener('click', () => {
    try {
      const data = items.map((it, i) => ({
        Objective: it.obj,
        Area: it.area,
        'Score (1-4)': scores[i] !== null ? scores[i] : '-',
        'Scale 1': it.scale1,
        'Scale 2': it.scale2,
        'Scale 3': it.scale3,
        'Scale 4': it.scale4
      }));
      
      if (typeof XLSX !== 'undefined') {
        const ws = XLSX.utils.json_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'MSME Categorization');
        XLSX.writeFile(wb, 'msme_categorization.xlsx');
      }
    } catch (error) {
      alert('Export failed: ' + error.message);
    }
  });

  // Theme toggle
  document.getElementById('themeToggle').onclick = () => {
    document.body.classList.toggle('dark');
    const btn = document.getElementById('themeToggle');
    btn.innerHTML = document.body.classList.contains('dark') ? 
      '<i class="fas fa-sun"></i> Light' : 
      '<i class="fas fa-moon"></i> Dark';
  };

  // Clear filter button (hidden but keep for compatibility)
  document.getElementById('clearFilterBtn').addEventListener('click', () => {});

  // Initialize
  function init() {
    renderProgressSteps();
    renderCurrentObjective();
    
    // Evaluator name change
    evaluatorNameInput.addEventListener('input', (e) => {
      evaluatorName = e.target.value;
    });
    
    // Enterprise select change
    enterpriseSelect.addEventListener('change', (e) => {
      selectedEnterprise = e.target.value;
    });
  }

  // Start when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
</script>
</body>
</html>