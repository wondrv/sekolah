import React from 'react';
import { createRoot } from 'react-dom/client';
import PageBuilder from './components/PageBuilder';

type PageBuilderData = {
  page: any;
  blocks: any[];
  availableBlocks: Record<string, any>;
  saveUrl: string;
  previewUrl: string;
  csrfToken: string;
};

declare global {
  interface Window {
    pageBuilderData?: PageBuilderData;
  }
}

function boot() {
  const el = document.getElementById('page-builder-app');
  if (!el) return;

  const data = window.pageBuilderData as PageBuilderData;
  if (!data) return;

  const root = createRoot(el);

  const handleSave = async (blocks: any[]) => {
    try {
      const res = await fetch(data.saveUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': data.csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ blocks }),
      });

      const json = await res.json();
      if (!res.ok || json.success === false) {
        console.error('Failed to save page:', json);
        alert('Gagal menyimpan halaman. Periksa konsol untuk detail.');
        return;
      }

      alert('Halaman berhasil disimpan.');
    } catch (e) {
      console.error(e);
      alert('Terjadi kesalahan saat menyimpan halaman.');
    }
  };

  root.render(
    <React.StrictMode>
      <PageBuilder
        initialBlocks={Array.isArray(data.blocks) ? data.blocks : []}
        availableBlocks={data.availableBlocks || {}}
        onSave={handleSave}
      />
    </React.StrictMode>
  );
}

document.addEventListener('DOMContentLoaded', boot);
