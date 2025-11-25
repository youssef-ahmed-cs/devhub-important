# Improved Class Diagram for Blogging Platform

## Enhanced Database Schema

### Core Tables

#### User
```
User
---
-ID: int PRIMARY KEY AUTO_INCREMENT UNSIGNED
-Name: varchar(255) NOT NULL
-Email: varchar(255) UNIQUE NOT NULL
-Password_hash: varchar(255) NOT NULL
-Bio: text
-Avatar_url: varchar(500)
-Email_verified: boolean DEFAULT false
-Email_verified_at: timestamp NULL
-Role: enum('user', 'admin', 'moderator') DEFAULT 'user'
-Status: enum('active', 'suspended', 'deleted') DEFAULT 'active'
-Last_login: timestamp NULL
-Created_at: timestamp DEFAULT CURRENT_TIMESTAMP
-Updated_at: timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-Deleted_at: timestamp NULL
---
+Register(data): User
+Login(email, password): Token
+Logout(): void
+UpdateProfile(data): void
+ChangePassword(oldPass, newPass): bool
+VerifyEmail(token): bool
+ResetPassword(token, newPass): bool
+WriteBlog(data): Blog
+ViewBlog(blogId): void
+WriteComment(blogId, content): Comment
+UseAIModel(): void
+ReactWithBlog(blogId, type): void
+EditBlog(blogId, data): void
+SoftDelete(): void
+HasPermission(permission): bool
+GetDashboardStats(): array
```

#### Blog
```
Blog
---
-ID: int PRIMARY KEY AUTO_INCREMENT UNSIGNED
-Author_id: int UNSIGNED FK(User.ID) NOT NULL
-Category_id: int UNSIGNED FK(Category.ID)
-Title: varchar(500) NOT NULL
-Slug: varchar(550) UNIQUE NOT NULL
-Body: longtext NOT NULL
-Excerpt: text
-Featured_image: varchar(500)
-Status: enum('draft', 'published', 'archived') DEFAULT 'draft'
-Views: int UNSIGNED DEFAULT 0
-Reading_time: int UNSIGNED
-Allow_comments: boolean DEFAULT true
-Published_at: timestamp NULL
-Created_at: timestamp DEFAULT CURRENT_TIMESTAMP
-Updated_at: timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-Deleted_at: timestamp NULL
---
+Create(data): Blog
+Update(data): void
+Publish(): void
+Unpublish(): void
+Archive(): void
+Delete(): void
+SoftDelete(): void
+IncrementViews(): void
+GetAuthor(): User
+GetCategory(): Category
+GetTags(): List<Tag>
+GetComments(status): List<Comment>
+GetReactions(): List<React>
+AddTag(tagId): void
+RemoveTag(tagId): void
+CalculateReadingTime(): int
+GetRelatedBlogs(limit): List<Blog>
+UseAIModelToWrite(): void
+ReactWithBlog(userId, type): void
```

#### Comment
```
Comment
---
-ID: int PRIMARY KEY AUTO_INCREMENT UNSIGNED
-Blog_id: int UNSIGNED FK(Blog.ID) NOT NULL
-User_id: int UNSIGNED FK(User.ID) NOT NULL
-Parent_id: int UNSIGNED FK(Comment.ID) NULL
-Body: text NOT NULL
-Status: enum('pending', 'approved', 'spam', 'deleted') DEFAULT 'pending'
-IP_address: varchar(45)
-User_agent: varchar(500)
-Created_at: timestamp DEFAULT CURRENT_TIMESTAMP
-Updated_at: timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-Deleted_at: timestamp NULL
---
+Create(data): Comment
+Update(body): void
+Delete(): void
+Approve(): void
+MarkAsSpam(): void
+GetReplies(): List<Comment>
+AddReply(data): Comment
+GetAuthor(): User
+GetBlog(): Blog
+GetParent(): Comment
+GetCommentCount(): int
+ReportComment(reason): void
```

#### React
```
React
---
-ID: int PRIMARY KEY AUTO_INCREMENT UNSIGNED
-Blog_id: int UNSIGNED FK(Blog.ID) NOT NULL
-User_id: int UNSIGNED FK(User.ID) NOT NULL
-Comment_id: int UNSIGNED FK(Comment.ID) NULL
-Type: enum('Like', 'Love', 'Support', 'Funny', 'Sad') NOT NULL
-Created_at: timestamp DEFAULT CURRENT_TIMESTAMP
-Updated_at: timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
---
UNIQUE KEY unique_blog_react (blog_id, user_id)
UNIQUE KEY unique_comment_react (comment_id, user_id)
---
+CreateOrUpdate(data): React
+Remove(): void
+GetReactionsByType(type): int
+GetUserReaction(userId, postId): React
+ToggleReaction(userId, postId, type): void
```

#### Notification
```
Notification
---
-ID: int PRIMARY KEY AUTO_INCREMENT UNSIGNED
-User_id: int UNSIGNED FK(User.ID) NOT NULL
-Actor_id: int UNSIGNED FK(User.ID)
-Notifiable_type: enum('comment', 'react', 'follow', 'mention', 'blog') NOT NULL
-Notifiable_id: int UNSIGNED NOT NULL
-Type: enum('info', 'success', 'warning', 'error') DEFAULT 'info'
-Title: varchar(255) NOT NULL
-Body: text
-Action_url: varchar(500)
-Is_read: boolean DEFAULT false
-Read_at: timestamp NULL
-Created_at: timestamp DEFAULT CURRENT_TIMESTAMP
---
+Create(data): Notification
+MarkAsRead(): void
+MarkAsUnread(): void
+Delete(): void
+GetUnreadCount(userId): int
+GetUserNotifications(userId, limit): List<Notification>
+MarkAllAsRead(userId): void
+DeleteOld(days): void
```

#### Follow
```
Follow
---
-ID: int PRIMARY KEY AUTO_INCREMENT UNSIGNED
-Follower_id: int UNSIGNED FK(User.ID) NOT NULL
-Followee_id: int UNSIGNED FK(User.ID) NOT NULL
-Created_at: timestamp DEFAULT CURRENT_TIMESTAMP
---
UNIQUE KEY unique_follow (follower_id, followee_id)
CHECK (follower_id != followee_id)
---
+Create(followerId, followeeId): Follow
+Remove(): void
+IsFollowing(followerId, followeeId): bool
+GetFollowers(userId): List<User>
+GetFollowing(userId): List<User>
+GetFollowerCount(userId): int
+GetFollowingCount(userId): int
+GetMutualFollowers(userId1, userId2): List<User>
```

#### Tag
```
Tag
---
-ID: int PRIMARY KEY AUTO_INCREMENT UNSIGNED
-Name: varchar(100) UNIQUE NOT NULL
-Slug: varchar(120) UNIQUE NOT NULL
-Description: text
-Color: varchar(7)
-Icon: varchar(50)
-Usage_count: int UNSIGNED DEFAULT 0
-Created_at: timestamp DEFAULT CURRENT_TIMESTAMP
-Updated_at: timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
---
+Create(data): Tag
+Update(data): void
+Delete(): void
+GetBlogs(limit): List<Blog>
+IncrementUsage(): void
+DecrementUsage(): void
+GetPopularTags(limit): List<Tag>
+Merge(targetTagId): void
```

#### Category
```
Category
---
-ID: int PRIMARY KEY AUTO_INCREMENT UNSIGNED
-Name: varchar(100) UNIQUE NOT NULL
-Slug: varchar(120) UNIQUE NOT NULL
-Description: text
-Parent_id: int UNSIGNED FK(Category.ID) NULL
-Icon: varchar(50)
-Order: int DEFAULT 0
-Is_active: boolean DEFAULT true
-Created_at: timestamp DEFAULT CURRENT_TIMESTAMP
-Updated_at: timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
---
+Create(data): Category
+Update(data): void
+Delete(): void
+GetBlogs(limit): List<Blog>
+GetSubcategories(): List<Category>
+GetParent(): Category
+GetBlogCount(): int
+Reorder(newOrder): void
```

#### BookMarks
```
BookMarks
---
-ID: int PRIMARY KEY AUTO_INCREMENT UNSIGNED
-Blog_id: int UNSIGNED FK(Blog.ID) NOT NULL
-User_id: int UNSIGNED FK(User.ID) NOT NULL
-Notes: text
-Created_at: timestamp DEFAULT CURRENT_TIMESTAMP
---
UNIQUE KEY unique_bookmark (blog_id, user_id)
---
+Create(userId, blogId): BookMarks
+Remove(): void
+Update(notes): void
+GetUserBookmarks(userId): List<Blog>
+IsBookmarked(userId, blogId): bool
```

#### BlogTag (Junction Table)
```
BlogTag
---
-ID: int PRIMARY KEY AUTO_INCREMENT UNSIGNED
-Blog_id: int UNSIGNED FK(Blog.ID) NOT NULL
-Tag_id: int UNSIGNED FK(Tag.ID) NOT NULL
-Created_at: timestamp DEFAULT CURRENT_TIMESTAMP
---
UNIQUE KEY unique_blog_tag (blog_id, tag_id)
---
+Create(blogId, tagId): BlogTag
+Remove(): void
```

#### Media
```
Media
---
-ID: int PRIMARY KEY AUTO_INCREMENT UNSIGNED
-User_id: int UNSIGNED FK(User.ID) NOT NULL
-Blog_id: int UNSIGNED FK(Blog.ID) NULL
-Filename: varchar(255) NOT NULL
-Original_filename: varchar(255)
-File_path: varchar(500) NOT NULL
-File_url: varchar(500) NOT NULL
-File_type: enum('image', 'video', 'document', 'audio') NOT NULL
-Mime_type: varchar(100)
-File_size: bigint UNSIGNED
-Width: int UNSIGNED
-Height: int UNSIGNED
-Alt_text: varchar(255)
-Caption: text
-Created_at: timestamp DEFAULT CURRENT_TIMESTAMP
---
+Upload(file, userId): Media
+Delete(): void
+GetUrl(): string
+GetThumbnail(size): string
+UpdateAltText(text): void
```

#### BlogRevision
```
BlogRevision
---
-ID: int PRIMARY KEY AUTO_INCREMENT UNSIGNED
-Blog_id: int UNSIGNED FK(Blog.ID) NOT NULL
-User_id: int UNSIGNED FK(User.ID) NOT NULL
-Title: varchar(500)
-Body: longtext
-Change_summary: varchar(500)
-Created_at: timestamp DEFAULT CURRENT_TIMESTAMP
---
+Create(blogId, data): BlogRevision
+GetRevisions(blogId): List<BlogRevision>
+Restore(revisionId): void
+Compare(revisionId1, revisionId2): array
```

## Relationships

1. **User → Blog**: One-to-Many (author_id)
2. **User → Comment**: One-to-Many (user_id)
3. **User → React**: One-to-Many (user_id)
4. **User → Follow**: One-to-Many (follower_id, followee_id)
5. **User → BookMarks**: One-to-Many (user_id)
6. **User → Notification**: One-to-Many (user_id, actor_id)
7. **User → Media**: One-to-Many (user_id)
8. **Blog → Comment**: One-to-Many (blog_id)
9. **Blog → React**: One-to-Many (blog_id)
10. **Blog → BookMarks**: One-to-Many (blog_id)
11. **Blog → Category**: Many-to-One (category_id)
12. **Blog → Tag**: Many-to-Many (via BlogTag)
13. **Blog → Media**: One-to-Many (blog_id)
14. **Blog → BlogRevision**: One-to-Many (blog_id)
15. **Comment → Comment**: One-to-Many (parent_id - self-referencing)
16. **Comment → React**: One-to-Many (comment_id)
17. **Category → Category**: One-to-Many (parent_id - self-referencing)

## Indexes for Performance

```sql
-- User indexes
CREATE INDEX idx_user_email ON User(Email);
CREATE INDEX idx_user_status ON User(Status);
CREATE INDEX idx_user_role ON User(Role);

-- Blog indexes
CREATE INDEX idx_blog_author ON Blog(Author_id);
CREATE INDEX idx_blog_category ON Blog(Category_id);
CREATE INDEX idx_blog_slug ON Blog(Slug);
CREATE INDEX idx_blog_status ON Blog(Status);
CREATE INDEX idx_blog_published ON Blog(Published_at);
CREATE FULLTEXT INDEX idx_blog_search ON Blog(Title, Body, Excerpt);

-- Comment indexes
CREATE INDEX idx_comment_blog ON Comment(Blog_id);
CREATE INDEX idx_comment_user ON Comment(User_id);
CREATE INDEX idx_comment_parent ON Comment(Parent_id);
CREATE INDEX idx_comment_status ON Comment(Status);

-- React indexes
CREATE INDEX idx_react_blog ON React(Blog_id);
CREATE INDEX idx_react_user ON React(User_id);
CREATE INDEX idx_react_comment ON React(Comment_id);

-- Follow indexes
CREATE INDEX idx_follow_follower ON Follow(Follower_id);
CREATE INDEX idx_follow_followee ON Follow(Followee_id);

-- Notification indexes
CREATE INDEX idx_notification_user ON Notification(User_id);
CREATE INDEX idx_notification_read ON Notification(Is_read);
CREATE INDEX idx_notification_created ON Notification(Created_at);

-- Tag indexes
CREATE INDEX idx_tag_slug ON Tag(Slug);
CREATE INDEX idx_tag_usage ON Tag(Usage_count);

-- BookMarks indexes
CREATE INDEX idx_bookmark_user ON BookMarks(User_id);
CREATE INDEX idx_bookmark_blog ON BookMarks(Blog_id);
```

## Key Improvements

### 1. **Data Integrity**
- Added proper foreign key constraints
- Added unique constraints where needed
- Added check constraints (e.g., users can't follow themselves)
- Proper data types with UNSIGNED for IDs

### 2. **Security**
- Password hashing
- IP address tracking for comments
- Soft deletes for data recovery
- Status fields for moderation

### 3. **Performance**
- Strategic indexes on frequently queried columns
- Fulltext search on blog content
- Composite unique keys for junction tables
- Optimized data types

### 4. **Audit Trail**
- Created_at, Updated_at timestamps
- BlogRevision table for version history
- Deleted_at for soft deletes
- User tracking in revisions

### 5. **Scalability**
- Media table separated for asset management
- Notification system with polymorphic relationships
- Tag usage tracking
- View counting

### 6. **User Experience**
- Reading time calculation
- Comment moderation workflow
- Bookmark notes
- Related blog suggestions
- Email verification

### 7. **Business Logic**
- Draft/Published workflow
- Comment approval system
- User roles and permissions
- Category hierarchy
- Tag management
```
