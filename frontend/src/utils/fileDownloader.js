import api from '../services/api';

/**
 * Trigger browser download for a Blob
 */
export function downloadBlob(data, filename) {
  const blob = data instanceof Blob ? data : new Blob([data], { type: 'text/csv;charset=utf-8;' });
  const url = window.URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.setAttribute('download', filename);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  window.URL.revokeObjectURL(url);
}

/**
 * Authenticated download helper via Axios
 */
export async function downloadAuthenticatedFile(endpoint, defaultFilename, params = {}) {
  try {
    const response = await api.get(endpoint, {
      params,
      responseType: 'blob',
    });

    let filename = defaultFilename;
    const disposition = response.headers['content-disposition'];
    if (disposition && disposition.indexOf('filename=') !== -1) {
      const match = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
      if (match != null && match[1]) {
        filename = match[1].replace(/['"]/g, '');
      }
    }

    downloadBlob(response.data, filename);
    return true;
  } catch (error) {
    console.error('Download error:', error);
    // If response was blob containing JSON error:
    if (error.response?.data instanceof Blob) {
      try {
        const text = await error.response.data.text();
        const json = JSON.parse(text);
        alert(json.message || 'Gagal mengunduh berkas.');
        return false;
      } catch (e) {
        // Fallback
      }
    }
    alert(error.response?.data?.message || 'Gagal mengunduh berkas dari server.');
    return false;
  }
}

