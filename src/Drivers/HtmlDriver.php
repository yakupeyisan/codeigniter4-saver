<?php

namespace Yakupeyisan\CodeIgniter4Saver\Drivers;

use Yakupeyisan\CodeIgniter4Saver\Exceptions\SaverException;

class HtmlDriver extends BaseDriver
{
    /**
     * Başlık
     *
     * @var string
     */
    protected string $title = 'Document';

    /**
     * Şablon
     *
     * @var string
     */
    protected string $template = 'default';

    /**
     * Özel CSS
     *
     * @var string
     */
    protected string $customCss = '';

    /**
     * Özel JS
     *
     * @var string
     */
    protected string $customJs = '';

    /**
     * Charset
     *
     * @var string
     */
    protected string $charset = 'UTF-8';

    /**
     * Constructor
     */
    public function __construct($config = null)
    {
        parent::__construct($config);
        $this->template = $this->config->html['default_template'];
        $this->charset = $this->config->html['charset'];
    }

    /**
     * Başlık belirler
     *
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Şablon belirler
     *
     * @param string $template
     * @return self
     */
    public function setTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Özel CSS ekler
     *
     * @param string $css
     * @return self
     */
    public function addCustomCss(string $css): self
    {
        $this->customCss .= $css;
        return $this;
    }

    /**
     * Özel JS ekler
     *
     * @param string $js
     * @return self
     */
    public function addCustomJs(string $js): self
    {
        $this->customJs .= $js;
        return $this;
    }

    /**
     * Charset belirler
     *
     * @param string $charset
     * @return self
     */
    public function setCharset(string $charset): self
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * HTML içeriğini oluşturur
     *
     * @return string
     */
    protected function buildHtml(): string
    {
        $templatePath = $this->config->html['template_path'] . $this->template . '.php';
        
        // Eğer özel şablon varsa kullan
        if (file_exists($templatePath)) {
            return $this->renderTemplate($templatePath);
        }
        
        // Yoksa varsayılan şablonu kullan
        return $this->renderDefaultTemplate();
    }

    /**
     * Şablonu render eder
     *
     * @param string $templatePath
     * @return string
     */
    protected function renderTemplate(string $templatePath): string
    {
        ob_start();
        extract([
            'title' => $this->title,
            'data' => $this->data,
            'customCss' => $this->customCss,
            'customJs' => $this->customJs,
            'charset' => $this->charset,
        ]);
        include $templatePath;
        return ob_get_clean();
    }

    /**
     * Varsayılan şablonu render eder
     *
     * @return string
     */
    protected function renderDefaultTemplate(): string
    {
        $tableHtml = $this->arrayToHtmlTable($this->data);
        
        $html = $this->config->html['doctype'] . "\n";
        $html .= "<html>\n";
        $html .= "<head>\n";
        $html .= "    <meta charset=\"{$this->charset}\">\n";
        $html .= "    <title>{$this->title}</title>\n";
        $html .= "    <style>\n";
        $html .= $this->getDefaultStyles();
        if (!empty($this->customCss)) {
            $html .= "    " . $this->customCss . "\n";
        }
        $html .= "    </style>\n";
        $html .= "</head>\n";
        $html .= "<body>\n";
        $html .= "    <div class=\"container\">\n";
        $html .= "        <h1>{$this->title}</h1>\n";
        $html .= "        {$tableHtml}\n";
        $html .= "    </div>\n";
        if (!empty($this->customJs)) {
            $html .= "    <script>\n";
            $html .= "    " . $this->customJs . "\n";
            $html .= "    </script>\n";
        }
        $html .= "</body>\n";
        $html .= "</html>";
        
        return $html;
    }

    /**
     * Varsayılan CSS stillerini döndürür
     *
     * @return string
     */
    protected function getDefaultStyles(): string
    {
        return <<<CSS
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
CSS;
    }

    /**
     * Array'i HTML tablosuna çevirir
     *
     * @param array $data
     * @return string
     */
    protected function arrayToHtmlTable(array $data): string
    {
        if (empty($data)) {
            return '<p>Veri bulunamadı.</p>';
        }

        $html = '<table>';
        
        foreach ($data as $index => $row) {
            $html .= '<tr>';
            $tag = $index === 0 ? 'th' : 'td';
            
            foreach ($row as $cell) {
                $html .= "<{$tag}>" . htmlspecialchars((string) $cell, ENT_QUOTES, $this->charset) . "</{$tag}>";
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        return $html;
    }

    /**
     * {@inheritDoc}
     */
    public function download(): void
    {
        $html = $this->buildHtml();
        $fileName = $this->getFileName('.html');

        header('Content-Type: ' . $this->getMimeType());
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        echo $html;
        exit;
    }

    /**
     * {@inheritDoc}
     */
    public function save(string $path): string
    {
        $this->ensureDirectoryWritable($path);

        $html = $this->buildHtml();
        $fileName = $this->getFileName('.html');
        $filePath = rtrim($path, '/\\') . DIRECTORY_SEPARATOR . $fileName;

        if (file_put_contents($filePath, $html) === false) {
            throw SaverException::forFileNotSaved($filePath);
        }

        return $filePath;
    }

    /**
     * {@inheritDoc}
     */
    public function toString(): string
    {
        return $this->buildHtml();
    }

    /**
     * {@inheritDoc}
     */
    protected function getMimeType(): string
    {
        return 'text/html';
    }
}

