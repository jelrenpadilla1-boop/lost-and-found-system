FROM php:8.2-fpm

# Install system dependencies + Nginx
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    libsqlite3-dev \
    libcurl4-openssl-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql mysqli zip bcmath exif opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Create custom php.ini (optimised for Laravel)
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini && \
    echo "max_execution_time=300" > /usr/local/etc/php/conf.d/max-execution-time.ini && \
    echo "max_input_time=300" > /usr/local/etc/php/conf.d/max-input-time.ini && \
    echo "upload_max_filesize=20M" > /usr/local/etc/php/conf.d/upload-max-filesize.ini && \
    echo "post_max_size=20M" > /usr/local/etc/php/conf.d/post-max-size.ini && \
    echo "max_file_uploads=20" > /usr/local/etc/php/conf.d/max-file-uploads.ini && \
    echo "opcache.enable=1" > /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Set Laravel storage permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy custom Nginx configuration
COPY conf/nginx/default.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/sites-enabled/default

# Remove default nginx site if present
RUN rm -f /etc/nginx/sites-enabled/default

# Expose dynamic port (Render will override with $PORT)
EXPOSE 8080

# Copy start script and make executable
COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]