<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Revisions · KPI + File Viewer · ASENXO</title>
<link rel="icon" type="image/png" href="ASENXO-WEB/favicon.png">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<link rel="stylesheet" href="../src/css/psto-ben-stat-style.css">
<link rel="stylesheet" href="../src/css/psto-revisions-style.css">

</head>
<body class="dark">
<div class="app">
<header class="top-header">
<div class="top-header-left">
<span class="project-name">ASENXO</span>
<span class="badge">REVISIONS VALIDATION</span>
<div class="search"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="Search MSME..."></div>
<div class="sort-filter-bar">
  <select id="sortSelect">
    <option value="name-asc">Name A-Z</option><option value="name-desc">Name Z-A</option>
    <option value="date-desc">Modified ▼</option><option value="date-asc">Modified ▲</option>
    <option value="count-desc">Files ▼</option><option value="count-asc">Files ▲</option>
  </select>
  <select id="folderFilterSelect">
    <option value="Project Proposals">Project Proposals</option>
    <option value="Supplier Quotation">Supplier Quotation</option>
    <option value="Realignment">Realignment</option>
    <option value="Restructuring">Restructuring</option>
    <option value="Extension">Extension</option>
  </select>
</div>
</div>
<div class="top-header-right">
  <button class="btn-export" id="exportBtn"><i class="fas fa-download"></i> Export</button>
  <button class="theme-toggle" id="themeToggle"><i class="fas fa-sun"></i> Light</button>
</div>
</header>

<div class="content-row">
<aside class="sidebar">
  <div class="sidebar-section"><div class="sidebar-header">MENU</div>
    <ul class="sidebar-menu">
      <li><a href="psto-home.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
      <li><a href="psto-ben-stat.php"><i class="fas fa-user-check"></i><span>Beneficiary Status</span></a></li>
      <li><a href="psto-map-view.php"><i class="fas fa-map-marked-alt"></i><span>Map View</span></a></li>
      <li><a href="psto-tna-tool.php"><i class="fas fa-clipboard-list"></i><span>TNA Tool</span></a></li>
      <li class="active"><a href="psto-revisions.php"><i class="fas fa-history"></i><span>Revisions</span></a></li>
      <li><a href="psto-endorsement.php"><i class="fas fa-check-circle"></i><span>Endorsement</span></a></li>
      <li><a href="psto-settings.php"><i class="fas fa-gear"></i><span>Settings</span></a></li>
    </ul>
  </div>
</aside>

<main class="main-content">
  <!-- KPI Section: Total files + per folder bar graphs -->
  <div class="kpi-grid" id="kpiContainer"></div>

  <!-- quick folder chips -->
  <div class="folder-row" id="folderChips"></div>

  <!-- breadcrumb -->
  <div class="breadcrumb" id="breadcrumb"></div>

  <!-- split panel -->
  <div class="split-panel">
    <div class="panel-left">
      <div class="panel-header"><i class="fas fa-building"></i> MSMEs</div>
      <div class="panel-body">
        <table>
          <thead><tr><th>MSME Name</th><th>Owner</th><th>Files</th><th>Last modified</th><th>Validation</th></tr></thead>
          <tbody id="tableBody"></tbody>
        </table>
      </div>
    </div>
    <div class="panel-right">
      <div class="panel-header"><i class="fas fa-folder-open"></i> <span id="selectedMsmeDisplay">Select an MSME</span></div>
      <div class="panel-body" id="fileListContainer"></div>
    </div>
  </div>
</main>
</div> <!-- .content-row -->
</div> <!-- .app -->

<!-- TRANSFER MODAL -->
<div class="modal-overlay" id="transferModal">
  <div class="modal">
    <i class="fas fa-paper-plane" style="font-size:28px; color:var(--green); margin-bottom:10px; display:block; text-align:center;"></i>
    <h3 style="margin:0 0 8px; text-align:center;">Transfer documents</h3>
    <p style="color:var(--text-muted); margin-bottom:20px; text-align:center;">Documents for <strong id="transferMsmeName"></strong> will be sent to DOST SETUP Admins.</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button class="modal-btn" id="transferCancel">Cancel</button>
      <button class="modal-btn confirm" id="transferConfirm">Confirm Transfer</button>
    </div>
  </div>
</div>

<script>
// ---------- DATA ----------
const revisionsData = {
  folders: [
    { name: 'Project Proposals', id: 'proposals' },
    { name: 'Supplier Quotation', id: 'quotation' },
    { name: 'Realignment', id: 'realign' },
    { name: 'Restructuring', id: 'restruct' },
    { name: 'Extension', id: 'extend' }
  ],
  msmes: {
    'proposals': [
      { id: 'msme1', name: 'Han Jim Marketing Corporation', owner: 'John Han' },
      { id: 'msme2', name: '4JNG Food Services', owner: 'Jane Ng' },
      { id: 'msme3', name: 'RMSS Garments Maker', owner: 'Ramon Santos' },
      { id: 'msme4', name: 'SJL Corporation', owner: 'Sarah Lopez' }
    ],
    'quotation': [
      { id: 'msme5', name: 'Maravilla Enterprises Inc.', owner: 'Mario Maravilla' },
      { id: 'msme6', name: 'JLP Multi Ventures, Inc.', owner: 'Jose Paterno' }
    ],
    'realign': [
      { id: 'msme7', name: 'Belverim Foods Corporation', owner: 'Belen Verim' },
      { id: 'msme8', name: 'Orchard Valley, Inc.', owner: 'Olivia Reyes' }
    ],
    'restruct': [
      { id: 'msme9', name: 'Think About Cakes Bakery', owner: 'Tina Cake' },
      { id: 'msme10', name: 'Balay Sang Amo Food Products', owner: 'Ben Amo' }
    ],
    'extend': [
      { id: 'msme11', name: 'Great Studios', owner: 'Greg Studio' }
    ]
  },
  files: {
    'msme1': [ { name: 'Proposal_rev2.docx', sharedBy: 'John Han', size:'1.4', unit:'MB', modified:'Apr 10, 2022', ext:'docx' }, { name: 'Financial_Projections.xlsx', sharedBy:'John Han', size:'0.9', unit:'MB', modified:'Apr 8, 2022', ext:'xlsx' } ],
    'msme2': [ { name: 'Business_plan_v3.pdf', sharedBy:'Jane Ng', size:'2.2', unit:'MB', modified:'Apr 5, 2022', ext:'pdf' } ],
    'msme3': [ { name: 'Garment_specs.docx', sharedBy:'Ramon Santos', size:'3.1', unit:'MB', modified:'Mar 28, 2022', ext:'docx' }, { name: 'Cost_estimate.ods', sharedBy:'Ramon Santos', size:'0.8', unit:'MB', modified:'Mar 27, 2022', ext:'ods' } ],
    'msme4': [ { name: 'SJL_Corporation_revised_proposal.pdf', sharedBy:'Sarah Lopez', size:'4.0', unit:'MB', modified:'Apr 1, 2022', ext:'pdf' }, { name: 'supporting_docs.zip', sharedBy:'Sarah Lopez', size:'12.5', unit:'MB', modified:'Mar 30, 2022', ext:'zip' } ],
    'msme5': [ { name: 'Quotation_2026.xlsx', sharedBy:'Mario Maravilla', size:'1.1', unit:'MB', modified:'Apr 2, 2022', ext:'xlsx' } ],
    'msme6': [ { name: 'Supplier_bid_JLP.pdf', sharedBy:'Jose Paterno', size:'0.7', unit:'MB', modified:'Mar 20, 2022', ext:'pdf' } ],
    'msme7': [ { name: 'Realignment_plan.docx', sharedBy:'Belen Verim', size:'2.3', unit:'MB', modified:'Mar 15, 2022', ext:'docx' } ],
    'msme8': [ { name: 'Orchard_Valley_restructure.pdf', sharedBy:'Olivia Reyes', size:'5.2', unit:'MB', modified:'Mar 10, 2022', ext:'pdf' } ],
    'msme9': [ { name: 'Cake_shop_extension_request.docx', sharedBy:'Tina Cake', size:'1.3', unit:'MB', modified:'Apr 7, 2022', ext:'docx' } ],
    'msme10': [ { name: 'Balay_Sang_Amo_revised_proposal.pdf', sharedBy:'Ben Amo', size:'3.7', unit:'MB', modified:'Apr 3, 2022', ext:'pdf' } ],
    'msme11': [ { name: 'Great_Studios_pitch_deck.pptx', sharedBy:'Greg Studio', size:'12.3', unit:'MB', modified:'Apr 9, 2022', ext:'pptx' }, { name: 'brand_assets.zip', sharedBy:'Greg Studio', size:'24.0', unit:'MB', modified:'Apr 8, 2022', ext:'zip' } ]
  },
  validation: {
    'msme1': 'pending', 'msme2': 'pending', 'msme3': 'pending', 'msme4': 'pending',
    'msme5': 'pending', 'msme6': 'pending', 'msme7': 'pending', 'msme8': 'pending',
    'msme9': 'pending', 'msme10': 'pending', 'msme11': 'validated'
  }
};

// ---------- state ----------
let currentFolderId = 'proposals';
let currentFolderFilter = 'Project Proposals';
let searchQuery = '';
let currentSort = 'name-asc';
let selectedMsmeId = 'msme1';

// ---------- helpers ----------
function getFolderIdFromName(name) {
  const f = revisionsData.folders.find(f => f.name === name);
  return f ? f.id : null;
}
function getFolderNameFromId(id) {
  const f = revisionsData.folders.find(f => f.id === id);
  return f ? f.name : id;
}

// ---------- KPI + bar graphs ----------
function renderKPI() {
  const container = document.getElementById('kpiContainer');
  container.innerHTML = '';
  let grandTotal = 0;
  revisionsData.folders.forEach(folder => {
    const msmes = revisionsData.msmes[folder.id] || [];
    let pending = 0, validated = 0, totalFiles = 0;
    msmes.forEach(msme => {
      const files = revisionsData.files[msme.id] || [];
      totalFiles += files.length;
      if (revisionsData.validation[msme.id] === 'pending') pending++;
      else if (revisionsData.validation[msme.id] === 'validated') validated++;
    });
    grandTotal += totalFiles;
    const totalMsme = pending + validated;
    const pendingPercent = totalMsme ? (pending / totalMsme) * 100 : 0;
    const validatedPercent = totalMsme ? (validated / totalMsme) * 100 : 0;
    container.innerHTML += `
      <div class="kpi-card" data-folder-id="${folder.id}">
        <div class="kpi-title">${folder.name} <span>${totalFiles}</span></div>
        <div class="kpi-bars">
          <div class="bar-pending" style="width: ${pendingPercent}%;"></div>
          <div class="bar-validated" style="width: ${validatedPercent}%;"></div>
        </div>
        <div class="kpi-stats">
          <span><span class="stat-dot dot-pending"></span> P:${pending}</span>
          <span><span class="stat-dot dot-validated"></span> V:${validated}</span>
        </div>
      </div>
    `;
  });
  // add total files card
  container.innerHTML += `
 
  `;
  // click on kpi card to filter
  document.querySelectorAll('.kpi-card[data-folder-id]').forEach(card => {
    card.addEventListener('click', () => {
      const fid = card.dataset.folderId;
      const folder = revisionsData.folders.find(f => f.id === fid);
      if (folder) {
        currentFolderId = fid;
        currentFolderFilter = folder.name;
        document.getElementById('folderFilterSelect').value = folder.name;
        selectedMsmeId = null;
        renderMsmeTable();
        renderFilePanel(null);
        renderBreadcrumb();
        renderFolderChips();
      }
    });
  });
}

// folder chips
function renderFolderChips() {
  const row = document.getElementById('folderChips');
  row.innerHTML = '';
  revisionsData.folders.forEach(f => {
    row.innerHTML += `<div class="folder-chip" data-folder-id="${f.id}" data-folder-name="${f.name}"><i class="fas fa-folder"></i> ${f.name}</div>`;
  });
  document.querySelectorAll('.folder-chip').forEach(chip => {
    chip.addEventListener('click', () => {
      currentFolderId = chip.dataset.folderId;
      currentFolderFilter = chip.dataset.folderName;
      document.getElementById('folderFilterSelect').value = currentFolderFilter;
      selectedMsmeId = null;
      renderMsmeTable();
      renderFilePanel(null);
      renderBreadcrumb();
    });
  });
}

// breadcrumb
function renderBreadcrumb() {
  const bc = document.getElementById('breadcrumb');
  bc.innerHTML = `
    <span class="breadcrumb-item" data-level="folder">Folders</span>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-item" data-level="msme">${getFolderNameFromId(currentFolderId)}</span>
  `;
  document.querySelector('.breadcrumb-item[data-level="folder"]').addEventListener('click', () => {
    currentFolderId = 'proposals';
    currentFolderFilter = 'Project Proposals';
    document.getElementById('folderFilterSelect').value = 'Project Proposals';
    selectedMsmeId = null;
    renderMsmeTable();
    renderFilePanel(null);
    renderBreadcrumb();
  });
}

// MSME table
function renderMsmeTable() {
  let msmeList = revisionsData.msmes[currentFolderId] || [];
  if (searchQuery) {
    msmeList = msmeList.filter(m => m.name.toLowerCase().includes(searchQuery) || m.owner.toLowerCase().includes(searchQuery));
  }
  const enriched = msmeList.map(m => {
    const files = revisionsData.files[m.id] || [];
    const lastMod = files.length ? files.sort((a,b)=> new Date(b.modified) - new Date(a.modified))[0].modified : '—';
    return { ...m, fileCount: files.length, lastMod, validation: revisionsData.validation[m.id] || 'pending' };
  });

  if (currentSort === 'name-asc') enriched.sort((a,b)=> a.name.localeCompare(b.name));
  else if (currentSort === 'name-desc') enriched.sort((a,b)=> b.name.localeCompare(a.name));
  else if (currentSort === 'date-desc') enriched.sort((a,b)=> (a.lastMod > b.lastMod ? -1 : 1));
  else if (currentSort === 'date-asc') enriched.sort((a,b)=> (a.lastMod < b.lastMod ? -1 : 1));
  else if (currentSort === 'count-desc') enriched.sort((a,b)=> b.fileCount - a.fileCount);
  else if (currentSort === 'count-asc') enriched.sort((a,b)=> a.fileCount - b.fileCount);

  const tbody = document.getElementById('tableBody');
  tbody.innerHTML = '';
  enriched.forEach(msme => {
    const statusClass = msme.validation === 'validated' ? 'status-validated' : 'status-pending';
    const statusText = msme.validation === 'validated' ? 'Transferred' : 'Pending';
    const disabled = msme.validation === 'validated' ? 'disabled' : '';
    const row = document.createElement('tr');
    row.innerHTML = `
      <td><div class="msme-name" data-msme-id="${msme.id}" data-msme-name="${msme.name}"><i class="fas fa-store"></i> ${msme.name}</div></td>
      <td>${msme.owner}</td>
      <td>${msme.fileCount}</td>
      <td>${msme.lastMod}</td>
      <td><span class="status-badge ${statusClass}">${statusText}</span> <button class="validate-btn" data-msme-id="${msme.id}" data-msme-name="${msme.name}" ${disabled}>Validate</button></td>
    `;
    tbody.appendChild(row);
  });

  document.querySelectorAll('.msme-name').forEach(cell => {
    cell.addEventListener('click', () => {
      selectedMsmeId = cell.dataset.msmeId;
      renderFilePanel(selectedMsmeId, cell.dataset.msmeName);
    });
  });

  document.querySelectorAll('.validate-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const msmeId = btn.dataset.msmeId;
      const msmeName = btn.dataset.msmeName;
      document.getElementById('transferMsmeName').innerText = msmeName;
      document.getElementById('transferModal').classList.add('active');
      window.__pendingValidateMsme = msmeId;
    });
  });

  if (!selectedMsmeId && enriched.length) {
    selectedMsmeId = enriched[0].id;
    renderFilePanel(selectedMsmeId, enriched[0].name);
  }
}

// file panel - click anywhere on item to open file
function renderFilePanel(msmeId, msmeName = '') {
  const container = document.getElementById('fileListContainer');
  const displayEl = document.getElementById('selectedMsmeDisplay');
  if (!msmeId) {
    displayEl.innerText = 'Select an MSME';
    container.innerHTML = `<div class="empty-state"><i class="fas fa-folder-open"></i><p>Click on an MSME to view files</p></div>`;
    return;
  }
  let foundName = msmeName;
  if (!foundName) {
    for (let f of revisionsData.folders) {
      const m = revisionsData.msmes[f.id]?.find(mm => mm.id === msmeId);
      if (m) { foundName = m.name; break; }
    }
  }
  displayEl.innerText = foundName || 'MSME';

  const files = revisionsData.files[msmeId] || [];
  if (files.length === 0) {
    container.innerHTML = `<div class="empty-state"><i class="fas fa-file"></i><p>No files</p></div>`;
    return;
  }

  let html = '';
  files.forEach(f => {
    const icon = f.ext === 'pdf' ? 'fa-file-pdf' : (f.ext === 'docx' ? 'fa-file-word' : (f.ext === 'xlsx' || f.ext === 'ods' ? 'fa-file-excel' : (f.ext === 'pptx' ? 'fa-file-powerpoint' : (f.ext === 'zip' ? 'fa-file-zipper' : 'fa-file-alt'))));
    html += `
      <div class="file-item" data-filename="${f.name}" data-ext="${f.ext}" data-sharedby="${f.sharedBy}" data-size="${f.size}" data-unit="${f.unit}" data-modified="${f.modified}">
        <i class="fas ${icon}"></i>
        <div class="file-details">
          <div class="file-name">${f.name}</div>
          <div class="file-meta">${f.sharedBy} • ${f.size}${f.unit} • ${f.modified}</div>
        </div>
      </div>
    `;
  });
  container.innerHTML = html;

  // Click anywhere on file item opens file (even after validation)
  document.querySelectorAll('.file-item').forEach(item => {
    item.addEventListener('click', () => {
      const fileName = item.dataset.filename;
      const ext = item.dataset.ext;
      // Simulate opening file in new tab
      const content = `This is a simulation of ${fileName}. In production, serve actual file.`;
      const blob = new Blob([content], { type: 'text/plain' });
      const url = URL.createObjectURL(blob);
      window.open(url, '_blank');
    });
  });
}

// transfer modal
document.getElementById('transferConfirm').onclick = () => {
  const msmeId = window.__pendingValidateMsme;
  if (msmeId) {
    revisionsData.validation[msmeId] = 'validated';
    renderMsmeTable();
    renderKPI();
    if (selectedMsmeId === msmeId) renderFilePanel(msmeId);
  }
  document.getElementById('transferModal').classList.remove('active');
  alert('Documents transferred to DOST SETUP Admins (simulated).');
};
document.getElementById('transferCancel').onclick = () => document.getElementById('transferModal').classList.remove('active');

// listeners
document.getElementById('searchInput').addEventListener('input', (e) => { searchQuery = e.target.value; renderMsmeTable(); });
document.getElementById('sortSelect').addEventListener('change', (e) => { currentSort = e.target.value; renderMsmeTable(); });
document.getElementById('folderFilterSelect').addEventListener('change', (e) => {
  currentFolderFilter = e.target.value;
  const folderId = getFolderIdFromName(currentFolderFilter);
  if (folderId) {
    currentFolderId = folderId;
    selectedMsmeId = null;
    renderMsmeTable();
    renderFilePanel(null);
    renderBreadcrumb();
  }
});
document.getElementById('themeToggle').onclick = function() {
  document.body.classList.toggle('dark');
  this.innerHTML = document.body.classList.contains('dark') ? '<i class="fas fa-sun"></i> Light' : '<i class="fas fa-moon"></i> Dark';
};
document.getElementById('exportBtn').onclick = () => alert('Export as XLSX');

// init
renderKPI();
renderFolderChips();
renderMsmeTable();
renderFilePanel(selectedMsmeId, 'Han Jim Marketing Corporation');
renderBreadcrumb();
</script>
</body>
</html>