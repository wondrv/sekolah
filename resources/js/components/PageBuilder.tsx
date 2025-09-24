import React, { useState, useEffect } from 'react';
import { DndContext, DragEndEvent, DragOverlay, DragStartEvent } from '@dnd-kit/core';
import { SortableContext, verticalListSortingStrategy, arrayMove } from '@dnd-kit/sortable';
import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';

interface BlockData {
    id: string;
    type: string;
    settings: Record<string, any>;
}

interface BlockConfig {
    name: string;
    category: string;
    icon: string;
    settings: Record<string, any>;
}

interface PageBuilderProps {
    initialBlocks?: BlockData[];
    availableBlocks: Record<string, BlockConfig>;
    onSave: (blocks: BlockData[]) => void;
}

// Sortable Block Item Component
const SortableBlockItem: React.FC<{
    block: BlockData;
    config: BlockConfig;
    onEdit: (block: BlockData) => void;
    onDelete: (blockId: string) => void;
}> = ({ block, config, onEdit, onDelete }) => {
    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
        isDragging,
    } = useSortable({ id: block.id });

    return (
        <div
            ref={setNodeRef}
            className={`group relative bg-white border-2 rounded-lg p-4 transition-all duration-200 ${
                isDragging 
                    ? 'border-blue-400 shadow-lg bg-blue-50 opacity-75 transform rotate-2' 
                    : 'border-gray-200 hover:border-blue-300 hover:shadow-md'
            }`}
            style={{
                transform: CSS.Transform.toString(transform),
                transition: transition,
            }}
        >
            {/* Drag Handle */}
            <div
                {...attributes}
                {...listeners}
                className="absolute top-2 left-2 cursor-grab active:cursor-grabbing text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100"
                title="Drag to reorder"
            >
                <i className="fas fa-grip-vertical text-sm"></i>
            </div>

            {/* Block Content */}
            <div className="ml-8">
                <div className="flex items-center justify-between mb-2">
                    <div className="flex items-center space-x-2">
                        <i className={`${config.icon} text-blue-500`}></i>
                        <span className="font-medium text-gray-900">{config.name}</span>
                        <span className="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                            {config.category}
                        </span>
                    </div>
                    
                    <div className="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button
                            onClick={() => onEdit(block)}
                            className="text-blue-600 hover:text-blue-800 text-sm p-2 rounded hover:bg-blue-50 transition-colors"
                            title="Edit block settings"
                        >
                            <i className="fas fa-edit"></i>
                        </button>
                        <button
                            onClick={() => {
                                if (confirm('Are you sure you want to remove this block?')) {
                                    onDelete(block.id);
                                }
                            }}
                            className="text-red-600 hover:text-red-800 text-sm p-2 rounded hover:bg-red-50 transition-colors"
                            title="Remove block"
                        >
                            <i className="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                {/* Block Preview */}
                <div className="text-sm text-gray-600">
                    {block.settings.title && (
                        <div className="font-medium">Title: {block.settings.title}</div>
                    )}
                    {block.settings.subtitle && (
                        <div>Subtitle: {block.settings.subtitle}</div>
                    )}
                    {Object.keys(block.settings).length === 0 && (
                        <div className="text-gray-400 italic">Click edit to configure settings</div>
                    )}
                    {Object.keys(block.settings).length > 0 && (
                        <div className="text-xs text-gray-400 mt-1">
                            {Object.keys(block.settings).length} setting(s) configured
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

// Block Settings Modal Component
const BlockSettingsModal: React.FC<{
    block: BlockData | null;
    config: BlockConfig | null;
    isOpen: boolean;
    onClose: () => void;
    onSave: (settings: Record<string, any>) => void;
}> = ({ block, config, isOpen, onClose, onSave }) => {
    const [settings, setSettings] = useState<Record<string, any>>({});

    useEffect(() => {
        if (block) {
            setSettings(block.settings || {});
        }
    }, [block]);

    // Add keyboard escape handler and body scroll lock
    useEffect(() => {
        const handleEscape = (e: KeyboardEvent) => {
            if (e.key === 'Escape' && isOpen) {
                onClose();
            }
        };

        if (isOpen) {
            document.addEventListener('keydown', handleEscape);
            document.body.style.overflow = 'hidden'; // Prevent body scroll
        }

        return () => {
            document.removeEventListener('keydown', handleEscape);
            document.body.style.overflow = 'unset'; // Restore body scroll
        };
    }, [isOpen, onClose]);

    if (!isOpen || !block || !config) return null;

    const handleSave = () => {
        onSave(settings);
        onClose();
    };

    const renderField = (key: string, fieldConfig: any) => {
        // Preserve boolean false with nullish coalescing; default empty string for text-like fields
        const rawValue = settings[key];
        const value = rawValue ?? (fieldConfig.type === 'checkbox' ? false : '');
        const fieldId = `block-${block?.id || 'new'}-${key}`;

        switch (fieldConfig.type) {
            case 'text':
                return (
                    <input
                        type="text"
                        id={fieldId}
                        value={value}
                        onChange={(e) => setSettings({ ...settings, [key]: e.target.value })}
                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder={fieldConfig.placeholder || fieldConfig.label || key}
                        title={fieldConfig.label || key}
                    />
                );
            
            case 'textarea':
                return (
                    <textarea
                        value={value}
                        onChange={(e) => setSettings({ ...settings, [key]: e.target.value })}
                        id={fieldId}
                        rows={4}
                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-y min-h-[100px]"
                        placeholder={fieldConfig.placeholder || ''}
                    />
                );

            case 'select':
                return (
                    <select
                        id={fieldId}
                        value={value}
                        onChange={(e) => setSettings({ ...settings, [key]: e.target.value })}
                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        title={fieldConfig.label || key}
                    >
                        <option value="" disabled={!!fieldConfig.required}>
                            {fieldConfig.placeholder || 'Select an option'}
                        </option>
                        {fieldConfig.options?.map((option: any, idx: number) => {
                            const isObject = option && typeof option === 'object';
                            const optValue = isObject
                                ? (option.value ?? option.id ?? option.key ?? '')
                                : option;
                            const optLabel = isObject
                                ? (option.label ?? option.name ?? String(optValue))
                                : option;
                            return (
                                <option key={String(optValue) || idx} value={optValue}>
                                    {optLabel}
                                </option>
                            );
                        })}
                    </select>
                );

            case 'checkbox':
                return (
                    <input
                        type="checkbox"
                        id={fieldId}
                        checked={Boolean(value)}
                        onChange={(e) => setSettings({ ...settings, [key]: e.target.checked })}
                        className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        aria-label={fieldConfig.label || key}
                    />
                );

            case 'url':
                return (
                    <input
                        type="url"
                        value={value}
                        onChange={(e) => setSettings({ ...settings, [key]: e.target.value })}
                        id={fieldId}
                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="https://example.com"
                    />
                );

            case 'image':
                return (
                    <div className="space-y-2">
                        <input
                            type="url"
                            value={value}
                            onChange={(e) => setSettings({ ...settings, [key]: e.target.value })}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Image URL or upload"
                            id={fieldId}
                        />
                        <button
                            type="button"
                            className="text-sm text-blue-600 hover:text-blue-800"
                        >
                            Upload Image
                        </button>
                    </div>
                );

            default:
                return (
                    <input
                        type="text"
                        value={value}
                        onChange={(e) => setSettings({ ...settings, [key]: e.target.value })}
                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder={fieldConfig.placeholder || fieldConfig.label || key}
                        title={fieldConfig.label || key}
                        id={fieldId}
                    />
                );
        }
    };

    return (
        <div 
            className="fixed inset-0 bg-black bg-opacity-50 flex items-start justify-center z-50 p-2 overflow-y-auto"
            onClick={(e) => {
                // Close modal when clicking on backdrop
                if (e.target === e.currentTarget) {
                    onClose();
                }
            }}
        >
            <div className="bg-white rounded-lg shadow-xl w-full max-w-2xl my-4 flex flex-col max-h-[95vh]">
                <div className="px-4 sm:px-6 py-4 border-b border-gray-200 flex-shrink-0 sticky top-0 bg-white rounded-t-lg z-10">
                    <div className="flex items-center justify-between">
                        <h3 className="text-lg font-medium text-gray-900">
                            Block Settings - {config?.name}
                        </h3>
                        <button
                            onClick={onClose}
                            className="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-full transition-colors"
                            title="Close modal (ESC)"
                        >
                            <i className="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <div className="flex-1 overflow-y-auto px-4 sm:px-6 py-4 min-h-0">
                    <div className="space-y-6">
                        {Object.entries(config.settings || {}).map(([key, fieldConfig]) => {
                            const fieldId = `block-${block?.id || 'new'}-${key}`;
                            return (
                                <div key={key} className="space-y-2">
                                    <label htmlFor={fieldId} className="block text-sm font-medium text-gray-700">
                                        {fieldConfig.label || key}
                                        {fieldConfig.required && <span className="text-red-500 ml-1">*</span>}
                                    </label>
                                    {fieldConfig.description && (
                                        <p className="text-xs text-gray-500">{fieldConfig.description}</p>
                                    )}
                                    {renderField(key, fieldConfig)}
                                </div>
                            );
                        })}
                        
                        {Object.keys(config.settings || {}).length === 0 && (
                            <div className="text-center py-8 text-gray-500">
                                <i className="fas fa-cog text-3xl mb-2"></i>
                                <p>This block has no configurable settings.</p>
                            </div>
                        )}
                    </div>
                </div>

                <div className="px-4 sm:px-6 py-4 border-t border-gray-200 flex justify-between items-center flex-shrink-0 bg-gray-50 rounded-b-lg sticky bottom-0">
                    <div className="text-sm text-gray-500">
                        Block ID: {block?.id}
                    </div>
                    <div className="flex space-x-3">
                        <button
                            onClick={onClose}
                            className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            onClick={handleSave}
                            className="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 transition-colors"
                        >
                            <i className="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

// Main Page Builder Component
const PageBuilder: React.FC<PageBuilderProps> = ({
    initialBlocks = [],
    availableBlocks,
    onSave,
}) => {
    const [blocks, setBlocks] = useState<BlockData[]>(initialBlocks);
    const [activeId, setActiveId] = useState<string | null>(null);
    const [editingBlock, setEditingBlock] = useState<BlockData | null>(null);
    const [sidebarExpanded, setSidebarExpanded] = useState(true);

    // Group blocks by category
    const blocksByCategory = React.useMemo(() => {
        const grouped: Record<string, Array<{ type: string; config: BlockConfig }>> = {};
        
        Object.entries(availableBlocks).forEach(([type, config]) => {
            const category = config.category || 'other';
            if (!grouped[category]) {
                grouped[category] = [];
            }
            grouped[category].push({ type, config });
        });

        return grouped;
    }, [availableBlocks]);

    const handleDragStart = (event: DragStartEvent) => {
        setActiveId(event.active.id as string);
    };

    const handleDragEnd = (event: DragEndEvent) => {
        const { active, over } = event;
        
        if (over && active.id !== over.id) {
            setBlocks((blocks) => {
                const oldIndex = blocks.findIndex((block) => block.id === active.id);
                const newIndex = blocks.findIndex((block) => block.id === over.id);
                
                return arrayMove(blocks, oldIndex, newIndex);
            });
        }
        
        setActiveId(null);
    };

    const addBlock = (type: string) => {
        const config = availableBlocks[type];
        if (!config) return;

        const newBlock: BlockData = {
            id: `block_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`,
            type,
            settings: {},
        };

        setBlocks([...blocks, newBlock]);
        setEditingBlock(newBlock);
    };

    const deleteBlock = (blockId: string) => {
        setBlocks(blocks.filter(block => block.id !== blockId));
    };

    const updateBlockSettings = (settings: Record<string, any>) => {
        if (!editingBlock) return;

        setBlocks(blocks.map(block => 
            block.id === editingBlock.id 
                ? { ...block, settings }
                : block
        ));
    };

    const handleSave = () => {
        onSave(blocks);
    };

    // Import/Export helpers
    const exportBlocks = () => {
        const blob = new Blob([JSON.stringify(blocks, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `page-blocks-${Date.now()}.json`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    };

    const importBlocks = async () => {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'application/json';
        input.onchange = async () => {
            const file = input.files?.[0];
            if (!file) return;
            try {
                const text = await file.text();
                const data = JSON.parse(text);
                if (!Array.isArray(data)) throw new Error('Invalid format');
                // Ensure blocks have ids; regenerate missing ones
                const normalized = data.map((b: any) => ({
                    id: b.id || `block_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`,
                    type: b.type,
                    settings: b.settings || {},
                }));
                setBlocks(normalized);
            } catch (e) {
                console.error('Import failed', e);
                alert('Gagal mengimpor JSON. Pastikan format benar.');
            }
        };
        input.click();
    };

    return (
        <div className="h-screen flex bg-gray-100">
            {/* Sidebar */}
            <div className={`bg-white border-r border-gray-200 flex flex-col transition-all duration-300 ${sidebarExpanded ? 'w-80' : 'w-16'}`}>
                <div className="p-4 border-b border-gray-200 flex items-center justify-between">
                    <button
                        onClick={() => setSidebarExpanded(!sidebarExpanded)}
                        className="text-gray-500 hover:text-gray-700"
                        title={sidebarExpanded ? "Collapse sidebar" : "Expand sidebar"}
                    >
                        <i className={`fas fa-${sidebarExpanded ? 'chevron-left' : 'chevron-right'}`}></i>
                    </button>
                </div>

                {sidebarExpanded && (
                    <div className="flex-1 overflow-y-auto p-4">
                        {Object.entries(blocksByCategory).map(([category, categoryBlocks]) => (
                            <div key={category} className="mb-6">
                                <h3 className="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">
                                    {category}
                                </h3>
                                <div className="space-y-2">
                                    {categoryBlocks.map(({ type, config }) => (
                                        <button
                                            key={type}
                                            onClick={() => addBlock(type)}
                                            className="w-full text-left p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors"
                                        >
                                            <div className="flex items-center space-x-3">
                                                <i className={`${config.icon} text-blue-500`}></i>
                                                <span className="text-sm font-medium text-gray-900">
                                                    {config.name}
                                                </span>
                                            </div>
                                        </button>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>

            {/* Main Content */}
            <div className="flex-1 flex flex-col">
                {/* Toolbar */}
                <div className="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h1 className="text-xl font-semibold text-gray-900">Page Builder</h1>
                    <div className="flex items-center space-x-4">
                        <button
                            onClick={exportBlocks}
                            className="px-3 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition-colors"
                            title="Export blocks as JSON"
                        >
                            <i className="fas fa-file-export mr-2"></i>
                            Export
                        </button>
                        <button
                            onClick={importBlocks}
                            className="px-3 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition-colors"
                            title="Import blocks from JSON"
                        >
                            <i className="fas fa-file-import mr-2"></i>
                            Import
                        </button>
                        <button
                            onClick={handleSave}
                            className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                        >
                            <i className="fas fa-save mr-2"></i>
                            Save Page
                        </button>
                    </div>
                </div>

                {/* Builder Area */}
                <div className="flex-1 overflow-y-auto p-6">
                    <div className="max-w-4xl mx-auto">
                        {blocks.length === 0 ? (
                            <div className="text-center py-12 bg-white rounded-lg border-2 border-dashed border-gray-300">
                                <div className="text-gray-400 text-6xl mb-4">
                                    <i className="fas fa-plus-circle"></i>
                                </div>
                                <h3 className="text-lg font-medium text-gray-900 mb-2">
                                    No blocks added yet
                                </h3>
                                <p className="text-gray-500">
                                    Select a block from the sidebar to get started
                                </p>
                            </div>
                        ) : (
                            <DndContext onDragStart={handleDragStart} onDragEnd={handleDragEnd}>
                                <SortableContext items={blocks.map(b => b.id)} strategy={verticalListSortingStrategy}>
                                    <div className="space-y-4">
                                        {activeId && (
                                            <div className="h-2 bg-blue-200 rounded-full opacity-50 animate-pulse border-2 border-dashed border-blue-400">
                                                <div className="text-center text-xs text-blue-600 mt-1">Drop zone</div>
                                            </div>
                                        )}
                                        {blocks.map((block, index) => (
                                            <div key={block.id}>
                                                <SortableBlockItem
                                                    block={block}
                                                    config={availableBlocks[block.type]}
                                                    onEdit={setEditingBlock}
                                                    onDelete={deleteBlock}
                                                />
                                                {activeId && index === blocks.length - 1 && (
                                                    <div className="h-2 bg-blue-200 rounded-full opacity-50 animate-pulse border-2 border-dashed border-blue-400 mt-4">
                                                        <div className="text-center text-xs text-blue-600 mt-1">Drop zone</div>
                                                    </div>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                </SortableContext>
                                
                                <DragOverlay>
                                    {activeId ? (
                                        <div className="bg-white border-2 border-blue-400 rounded-lg p-4 shadow-2xl opacity-90 transform rotate-3 scale-105">
                                            <div className="flex items-center space-x-3">
                                                <i className="fas fa-grip-vertical text-blue-400"></i>
                                                <i className={`${availableBlocks[blocks.find(b => b.id === activeId)?.type || '']?.icon} text-blue-500`}></i>
                                                <span className="font-medium text-gray-900">
                                                    {availableBlocks[blocks.find(b => b.id === activeId)?.type || '']?.name}
                                                </span>
                                                <span className="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded">
                                                    Dragging...
                                                </span>
                                            </div>
                                        </div>
                                    ) : null}
                                </DragOverlay>
                            </DndContext>
                        )}
                    </div>
                </div>
            </div>

            {/* Block Settings Modal */}
            <BlockSettingsModal
                block={editingBlock}
                config={editingBlock ? availableBlocks[editingBlock.type] : null}
                isOpen={!!editingBlock}
                onClose={() => setEditingBlock(null)}
                onSave={updateBlockSettings}
            />
        </div>
    );
};

export default PageBuilder;